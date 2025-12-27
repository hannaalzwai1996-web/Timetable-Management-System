<?php

use App\Http\Controllers\Supervisor\gradeController;
use App\Http\Controllers\Supervisor\subjectController;
use App\Http\Controllers\Supervisor\teacherController;
use App\Http\Controllers\Supervisor\sectionController;
use App\Http\Controllers\Supervisor\time_sessionCo;
use App\Http\Controllers\Supervisor\time_slotCo;
use App\Http\Controllers\Supervisor\timeTableController;
use App\Http\Controllers\Registrar\RegistrarDashboardController;
use App\Http\Controllers\Registrar\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Registrar\SectionController as RegistrarSectionController;
// Supervisor Routes

Route::resource('grade', gradeController::class)->names([
    'index'=>'grade.index',
    'create'=>'grade.create',
    'show'=>'grade.show',
    'edit'=>'grade.edit',
    'store'=>'grade.store',
    'update'=>'grade.update',
    'destroy'=>'grade.destroy',
]);

Route::resource('section', sectionController::class)->names([
    'index'=>'section.index',
    'create'=>'section.create',
    'show'=>'section.show',
    'edit'=>'section.edit',
    'store'=>'section.store',
    'update'=>'section.update',
    'destroy'=>'section.destroy',
]);

Route::resource('subject', subjectController::class)->names([
    'index'=>'subject.index',
    'create'=>'subject.create',
    'show'=>'subject.show',
    'edit'=>'subject.edit',
    'store'=>'subject.store',
    'update'=>'subject.update',
    'destroy'=>'subject.destroy',
]);

Route::resource('teacher', teacherController::class)->names([
    'index'=>'teacher.index',
    'create'=>'teacher.create',
    'show'=>'teacher.show',
    'edit'=>'teacher.edit',
    'store'=>'teacher.store',
    'update'=>'teacher.update',
    'destroy'=>'teacher.destroy',
]);

Route::resource('timetable', timeTableController::class)->names([
    'index'=>'timetable.index',
    'create'=>'timetable.create',
    'store'=>'timetable.store',
    'show'=>'timetable.show',
    'edit'=>'timetable.edit',
    'update'=>'timetable.update',
    'destroy'=>'timetable.destroy',
]);

Route::resource('timeSession', time_sessionCo::class)->names([
    'index'=>'timeSession.index',
    'create'=>'timeSession.create',
    'store'=>'timeSession.store',
    'show'=>'timeSession.show',
    'edit'=>'timeSession.edit',
    'update'=>'timeSession.update',
    'destroy'=>'timeSession.destroy',
]);

Route::resource('timeslots', time_slotCo::class)->names([
    'index'=>'timeslots.index',
    'create'=>'timeslots.create',
    'store'=>'timeslots.store',
    'edit'=>'timeslots.edit',
    'update'=>'timeslots.update',
    'destroy'=>'timeslots.destroy',
]);

// Registrar Routes

Route::prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', [RegistrarDashboardController::class,'index'])->name('dashboard');
    Route::resource('students', StudentController::class);
});

Route::get('/registrar/sections/by-grade/{grade}', [StudentController::class, 'sectionsByGrade'])
    ->name('registrar.sections.byGrade');

Route::get('/registrar/sections/by-grade/{gradeId}', [RegistrarSectionController::class, 'byGrade']);
Route::get('students/{student}/transfer', [StudentController::class, 'transferForm'])
    ->name('students.transfer.form');

Route::post('students/{student}/transfer', [StudentController::class, 'transferStore'])
    ->name('students.transfer.store');
Route::prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', [RegistrarDashboardController::class,'index'])->name('dashboard');
    Route::resource('students', StudentController::class);
    // تواجد مسار الترحيل
    Route::get('students/{student}/transfer', [StudentController::class, 'transferForm'])
        ->name('students.transfer.form');
    Route::post('students/{student}/transfer', [StudentController::class, 'transferStore'])
        ->name('students.transfer.store');
});

Route::get('/registrar/sections/by-grade/{grade}', [StudentController::class, 'sectionsByGrade'])
    ->name('registrar.sections.byGrade');
Route::get('/registrar/sections/by-grade/{gradeId}', [RegistrarSectionController::class, 'byGrade']);