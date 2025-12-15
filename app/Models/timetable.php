<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class timetable extends Model
{
     protected $fillable=[
        "grade_id",
        "section_id",
        "year"
     ];
     public function grade(){
        return $this->belongsTo(grade::class,"grade_id","id");
     }

     public function section(){
        return $this->belongsTo(section::class,"section_id","id");
     }
     public function sessions()
{
    return $this->hasMany(time_sessions::class, 'timetable_id');
}

}
