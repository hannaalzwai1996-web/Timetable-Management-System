<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\time_sessions;
use App\Models\Time_slots;
use Illuminate\Http\Request;
use App\Models\TimeSession;
use App\Models\TimeSlot;
use App\Models\Timetable;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Section;

class time_sessionCo extends Controller
{
   
    public function index()
    {
        $sessions = time_sessions::with(['timeslot', 'subject', 'teacher', 'section', 'timetable'])
            ->get();

        return view('Supervisor.time_sessions.index', compact('sessions'));
    }

  
    public function create(Request $request)
    {
        $timetable = Timetable::findOrFail($request->timetable_id);

        $slots    = Time_slots::orderBy('day')->orderBy('start_time')->get();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $sections = Section::where('grade_id', $timetable->grade_id)->get();

        return view('Supervisor.time_sessions.create', compact(
            'timetable',
            'slots',
            'subjects',
            'teachers',
            'sections'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'timetable_id' => 'required|exists:timetables,id',
            'timeslot_id'  => 'required|exists:time_slots,id',
            'subject_id'   => 'required|exists:subjects,id',
            'teacher_id'   => 'required|exists:teachers,id',
            'section_id'   => 'required|exists:sections,id',
        ]);

  
        $conflict = time_sessions::where('timeslot_id', $request->timeslot_id)
            ->where('section_id', $request->section_id)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['error' => 'يوجد تعارض في هذا الوقت!'])->withInput();
        }

        time_sessions::create([
            'timetable_id' => $request->timetable_id,
            'timeslot_id'  => $request->timeslot_id,
            'subject_id'   => $request->subject_id,
            'teacher_id'   => $request->teacher_id,
            'section_id'   => $request->section_id,
        ]);

        return redirect()->route('timetable.show', $request->timetable_id)
            ->with('success', 'تمت إضافة الحصة بنجاح');
    }

   
    public function show(time_sessions $time_sessions)
    {

        return view('Supervisor.time_sessions.show', compact('session'));
    }

  
    public function edit(time_sessions $time_sessions)
    {

        $timetable = $time_sessions->timetable;

        $slots    = Time_slots::orderBy('day')->orderBy('start_time')->get();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $sections = Section::where('grade_id', $timetable->grade_id)->get();

        return view('Supervisor.time_sessions.edit', compact(
            'session',
            'slots',
            'subjects',
            'teachers',
            'sections',
            'timetable'
        ));
    }

    public function update(Request $request, time_sessions $time_sessions)
    {
        

        $request->validate([
            'timeslot_id'  => 'required|exists:time_slots,id',
            'subject_id'   => 'required|exists:subjects,id',
            'teacher_id'   => 'required|exists:teachers,id',
            'section_id'   => 'required|exists:sections,id',
        ]);

      
        $conflict = time_sessions::where('timeslot_id', $request->timeslot_id)
            ->where('section_id', $request->section_id)
            ->where('id', '!=', $time_sessions)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['error' => 'يوجد تعارض في هذا الوقت!'])->withInput();
        }

        $time_sessions->update([
            'timeslot_id'  => $request->timeslot_id,
            'subject_id'   => $request->subject_id,
            'teacher_id'   => $request->teacher_id,
            'section_id'   => $request->section_id,
        ]);

        return redirect()->route('timetable.show', $time_sessions->timetable_id)
            ->with('success', 'تم تحديث الحصة بنجاح');
    }

  
    public function destroy(time_sessions $time_sessions)
    {
    
        $timetable_id = $time_sessions->timetable_id;

        $time_sessions->delete();

        return redirect()->route('timetable.show', $timetable_id)
            ->with('success', 'تم حذف الحصة بنجاح');
    }
}
