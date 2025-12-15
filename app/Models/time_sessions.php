<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class time_sessions extends Model
{


    protected $fillable = [
        'timetable_id',
        'timeslot_id',
        'subject_id',
        'teacher_id',
        'section_id',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function timeslot()
    {
        return $this->belongsTo(Time_slots::class, 'timeslot_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
