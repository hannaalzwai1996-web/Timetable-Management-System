<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\teacher;
use App\Models\User;
use Collator;
use Illuminate\Http\Request;

class teacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher=teacher::with("user")->get();
        return view("Supervisor.teacher.index",compact("teacher"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user=User::all();
        return view("Supervisor.teacher.create",compact("user"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $input = $request->validate([
    'user_id'    => ['required', 'exists:users,id'],
    'full_name'  => ['required'],
    'phonenumber'=> ['required'],
]);

        teacher::create($input);
        return redirect(route("teacher.index"))->with("success","added successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(teacher $teacher)
    {
        return view("Supervisor.teacher.details",compact("teacher"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(teacher $teacher)
    {
        
        $user=User::all();
        return view("Supervisor.teacher.edit",compact("teacher","user"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, teacher $teacher)
    {
       $input = $request->validate([
    'user_id'    => ['required', 'exists:users,id'],
    'full_name'  => ['required'],
    'phonenumber'=> ['required'],
]);

        $teacher->update($input);
        return redirect(route("teacher.index"))->with("success","updated successfully");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(teacher $teacher)
    {
        $teacher->delete();
        return redirect(route("teacher.index"))->with("success","deleted successfully");
    }
}
