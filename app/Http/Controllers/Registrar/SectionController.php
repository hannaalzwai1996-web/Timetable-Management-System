<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function byGrade($gradeId)
    {
        $sections = Section::where('grade_id', $gradeId)
            ->select('id', 'name')
            ->distinct()
            ->get();

        return response()->json($sections);
    }
}