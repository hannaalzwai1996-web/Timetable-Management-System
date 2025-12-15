<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time_slots extends Model
{

    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'duration',
    ];

    public function sessions()
    {
        return $this->hasMany(time_sessions::class, 'timeslot_id');
    }
}
