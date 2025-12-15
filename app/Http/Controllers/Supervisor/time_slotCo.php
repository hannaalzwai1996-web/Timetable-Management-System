<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Time_slots;
use App\Models\timeSlot;
use Illuminate\Http\Request;

class time_slotCo extends Controller
{
    /**
     * عرض كل التوقيتات (time_slots)
     */
    public function index()
    {
        $timeslots = Time_slots::orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('Supervisor.time_slots.index', compact('timeslots'));
    }

    /**
     * صفحة إنشاء توقيت جديد
     */
    public function create()
    {
        return view('Supervisor.time_slots.create');
    }

    /**
     * حفظ توقيت جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'day'        => ['required'],          // ممكن تخليها in:sat,sun,... لو حابة
            'start_time' => ['required'],
            'end_time'   => ['required'],
            'duration'   => ['nullable', 'integer', 'min:1'],
        ]);

        Time_slots::create([
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'duration'   => $request->duration,
        ]);

        return redirect()->route('time_slots.index')
            ->with('success', 'تمت إضافة التوقيت بنجاح');
    }

    /**
     * عرض توقيت معيّن (لو احتجتيه)
     */
    public function show(Time_slots $time_slot)
    {

        return view('Supervisor.time_slots.show');
    }

    /**
     * صفحة تعديل توقيت
     */
    public function edit(string $id)
    {

        return view('Supervisor.time_slots.edit');
    }

    /**
     * تحديث بيانات التوقيت
     */
    public function update(Request $request, Time_slots $time_slot)
    {
        $request->validate([
            'day'        => ['required'],
            'start_time' => ['required'],
            'end_time'   => ['required'],
            'duration'   => ['nullable', 'integer', 'min:1'],
        ]);



        $time_slot->update([
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'duration'   => $request->duration,
        ]);

        return redirect()->route('time_slots.index')
            ->with('success', 'تم تحديث التوقيت بنجاح');
    }

    /**
     * حذف توقيت
     */
    public function destroy(Time_slots $time_slot)
    {
      
        $time_slot->delete();

        return redirect()->route('time_slots.index')
            ->with('success', 'تم حذف التوقيت بنجاح');
    }
}
