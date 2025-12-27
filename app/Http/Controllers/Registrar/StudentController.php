<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    
    public function index()
    {
        $students = Student::with(['grade','section'])->get();
        return view('Registrar.students.index', compact('students'));
    }

    public function create()
    {
        $grades = Grade::all();
        $sections = Section::all();
        return view('Registrar.students.create', compact('grades','sections'));
    }
public function store(Request $request)
{
    $data = $request->validate([
        'full_name'     => 'required|string|max:255',
        'email'         => 'required|email|max:255',
        'password'      => 'required|string|min:6',
        'grade_id'      => 'required|exists:grades,id',
        'section_id'    => 'required|exists:sections,id',
        'date_of_birth' => 'nullable|date',
    ]);

    // ✅ لو الإيميل موجود وعنده طالب مسبقاً → نوقف
    $existingUser = User::where('email', $data['email'])->first();
    if ($existingUser && Student::where('user_id', $existingUser->id)->exists()) {
        throw ValidationException::withMessages([
            'email' => '⚠️ الطالب موجود بالفعل بنفس البريد الإلكتروني',
        ]);
    }

    // ✅ (اختياري) منع تكرار الاسم داخل نفس الصف/الشعبة
    $sameStudent = Student::where('full_name', $data['full_name'])
        ->where('grade_id', $data['grade_id'])
        ->where('section_id', $data['section_id'])
        ->exists();

    if ($sameStudent) {
        throw ValidationException::withMessages([
            'full_name' => '⚠️ الطالب موجود بالفعل في نفس الصف والشعبة',
        ]);
    }

    DB::transaction(function () use ($data, $existingUser) {

        $user = $existingUser ?: User::create([
            'name'     => $data['full_name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role'     => 'student',
        ]);

        Student::create([
            'user_id'        => $user->id,
            'full_name'      => $data['full_name'],
            'grade_id'       => $data['grade_id'],
            'section_id'     => $data['section_id'],
            'date_of_birth'  => $data['date_of_birth'] ?? null,
        ]);
    });

    return redirect()
        ->route('registrar.students.index')
        ->with('success', '✅ تم إضافة الطالب بنجاح');
}
public function edit(Student $student)
{
    $grades = Grade::all();
    $sections = Section::where('grade_id', $student->grade_id)->get();

    $student->load('user');

    return view('Registrar.students.edit', compact('student','grades','sections'));
}

public function update(Request $request, Student $student)
{
    $data = $request->validate([
        'full_name'     => 'required|string|max:255',
        'email'         => 'required|email|max:255',
        'grade_id'      => 'required|exists:grades,id',
        'section_id'    => 'required|exists:sections,id',
        'date_of_birth' => 'nullable|date',
    ]);

    DB::transaction(function () use ($student, $data) {
        // تحديث student
        $student->update([
            'full_name'     => $data['full_name'],
            'grade_id'      => $data['grade_id'],
            'section_id'    => $data['section_id'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
        ]);

        // تحديث user email + name
        if ($student->user) {
            // منع تكرار الإيميل مع تجاهل نفس المستخدم الحالي
            $exists = User::where('email', $data['email'])
                ->where('id', '!=', $student->user->id)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'email' => '⚠️ البريد مستخدم من طالب آخر',
                ]);
            }

            $student->user->update([
                'name'  => $data['full_name'],
                'email' => $data['email'],
            ]);
        }
    });

    return redirect()
        ->route('registrar.students.index')
        ->with('success', '✅ تم تحديث بيانات الطالب');
}
public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        $student->delete();
        $user->delete();

        return redirect()->route('registrar.students.index')->with('success','تم حذف الطالب بنجاح');
    }
   public function sectionsByGrade($gradeId)
{
    $sections = \App\Models\section::where('grade_id', $gradeId)
        ->select('id','name')
        ->distinct()
        ->get();

    return response()->json($sections);
}
public function show($id)
{
    // مش مستخدمة حالياً
    return redirect()->route('registrar.students.index');
}
public function transferForm(Student $student)
{
    $grades = Grade::all();
    $sections = Section::where('grade_id', $student->grade_id)->get();

    return view('Registrar.students.transfer', compact('student','grades','sections'));
}

public function transferStore(Request $request, Student $student)
{
    $data = $request->validate([
        'grade_id'   => 'required|exists:grades,id',
        'section_id' => 'required|exists:sections,id',
    ]);

    $student->update([
        'grade_id'   => $data['grade_id'],
        'section_id' => $data['section_id'],
    ]);

    return redirect()
        ->route('registrar.students.index')
        ->with('success', '✅ تم ترحيل الطالب بنجاح');
}
}