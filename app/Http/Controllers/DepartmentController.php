<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(){
        $deptData = Department::get();
        return view("department.index",compact("deptData"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department' => 'required|string|max:255',
        ]);
    
        // Check if a Department with the same name already exists
        $existingDepartment = Department::where('dept', $request->department)->first();
    
        if ($existingDepartment) {
            // Department with the same name already exists, handle accordingly
            return redirect()->back()->with('failed', 'Department with this name already exists.');
        }
    
        // Department with the same name does not exist, create a new record
        try {
            Department::create([
                'dept' => $request->department,
            ]);
    
            return redirect()->back()->with('status', 'Department created successfully');
        } catch (\Exception $e) {
            // Handle any exception that may occur during the creation
            return redirect()->back()->with('failed', 'Failed to create department. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department' => 'required|string|max:255',
        ]);

        $department = Department::findOrFail($id);

        // Get the original department name
        $originalDepartmentName = $department->dept;

        // Update the model attribute
        $department->update([
            'dept' => $request->department,
        ]);

        // Check if the department name has changed
        if ($originalDepartmentName != $request->department) {
            return redirect()->back()->with('status', 'Department updated successfully');
        } else {
            // No changes, so no update is needed
            return redirect()->back()->with('failed', 'No changes made to the department.');
        }
    }

    
    
}
