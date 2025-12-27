<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;

class RegistrarDashboardController extends Controller
{
    public function index()
    {
        $studentsCount = Student::count();
        return view('Register.dashboard', compact('studentsCount'));
    }
}
