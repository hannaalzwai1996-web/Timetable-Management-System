<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\section;
use App\Models\time_sessions;
use App\Models\Time_slots;
use App\Models\timetable;
use App\Models\grade;
use App\Models\timeSlot;
use App\Models\subject;
use App\Models\teacher;
use App\Models\TimeSession;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class timeTableController extends Controller
{
    /**
     * عرض جميع الجداول الدراسية
     */
    public function index()
    {
        $timetables = timetable::with(['grade', 'section'])
            ->orderByDesc('year')
            ->orderBy('grade_id')
            ->orderBy('section_id')
            ->get();

        return view('Supervisor.timetable.index', compact('timetables'));
    }

    /**
     * فورم إنشاء جدول جديد (لصف + فصل + سنة)
     */
    public function create()
    {
        $grades   = grade::all();
        $sections = section::all(); // ممكن لاحقًا تصفيها بالـ JS حسب الصف

        return view('Supervisor.timetable.create', compact('grades', 'sections'));
    }

    /**
     * حفظ جدول جديد
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_id'   => ['required', 'exists:grades,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'year'       => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
                // منع تكرار جدول لنفس (الصف + الفصل + السنة)
                Rule::unique('timetables')->where(function ($q) use ($request) {
                    return $q->where('grade_id', $request->grade_id)
                             ->where('section_id', $request->section_id);
                }),
            ],
        ]);

        $timetable = timetable::create($data);

        return redirect()
            ->route('timetable.show', $timetable->id)
            ->with('success', 'تم إنشاء الجدول الدراسي بنجاح.');
    }

    /**
     * عرض جدول معيّن + حصصه
     */
    public function show(Timetable $timetable)
    {
        // نحمّل الصف والفصل
        $timetable->load(['grade', 'section']);

        // كل التوقيتات (time_slots)
        $timeslots = Time_slots::orderBy('day')
            ->orderBy('start_time')
            ->get();

        // الجلسات (الحصص) لهذا الجدول
        $sessions = time_sessions::with(['timeslot', 'subject', 'teacher', 'section'])
            ->where('timetable_id', $timetable->id)
            ->get();

        // لو تحتاجيهم في فورم الإضافة / التعديل
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $sections = Section::where('grade_id', $timetable->grade_id)->get();

        return view('Supervisor.timetable.show', compact(
            'timetable',
            'timeslots',
            'sessions',
            'subjects',
            'teachers',
            'sections'
        ));
    }


    /**
     * تعديل بيانات الجدول (الصف + الفصل + السنة)
     */
    public function edit(timetable $timetable)
    {
        $grades   = grade::all();
        $sections = section::all();

        return view('Supervisor.timetable.edit', compact('timetable', 'grades', 'sections'));
    }

    /**
     * حفظ تعديل الجدول
     */
    public function update(Request $request, timetable $timetable)
    {
        $data = $request->validate([
            'grade_id'   => ['required', 'exists:grades,id'],
            'section_id' => ['required', 'exists:sections,id'],
            'year'       => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
                Rule::unique('timetables')
                    ->ignore($timetable->id)
                    ->where(function ($q) use ($request) {
                        return $q->where('grade_id', $request->grade_id)
                                 ->where('section_id', $request->section_id);
                    }),
            ],
        ]);

        $timetable->update($data);

        return redirect()
            ->route('timetable.index')
            ->with('success', 'تم تحديث بيانات الجدول بنجاح.');
    }

    /**
     * حذف جدول (ومعه الحصص التابعة له بسبب cascadeOnDelete)
     */
    public function destroy(timetable $timetable)
    {
        $timetable->delete();

        return redirect()
            ->route('timetable.index')
            ->with('success', 'تم حذف الجدول الدراسي بنجاح.');
    }
}
