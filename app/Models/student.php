<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Grade;
use App\Models\Section;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'grade_id', 'section_id', 'date_of_birth'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function grade() {
        return $this->belongsTo(Grade::class);
    }

    public function section() {
        return $this->belongsTo(Section::class);
    }
}
