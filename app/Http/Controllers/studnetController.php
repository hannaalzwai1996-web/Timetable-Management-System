<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudnetController extends Controller
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
        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'grade_id' => 'required',
            'section_id' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student'
        ]);

        Student::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'grade_id' => $request->grade_id,
            'section_id' => $request->section_id,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('registrar.students.index')->with('success','تم تسجيل الطالب بنجاح');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $grades = Grade::all();
        $sections = Section::all();
        return view('Registrar.students.edit', compact('student','grades','sections'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'full_name' => 'required',
            'grade_id' => 'required',
            'section_id' => 'required',
            'date_of_birth' => 'required|date',
        ]);

        $student->update([
            'full_name' => $request->full_name,
            'grade_id' => $request->grade_id,
            'section_id' => $request->section_id,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('registrar.students.index')->with('success','تم تعديل بيانات الطالب بنجاح');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->user()->delete(); // تحذف المستخدم المرتبط
        $student->delete();

        return redirect()->route('registrar.students.index')->with('success','تم حذف الطالب بنجاح');
    }
}
