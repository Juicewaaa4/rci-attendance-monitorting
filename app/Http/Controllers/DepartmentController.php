<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DepartmentController
{

    public function index(Request $request)
    {
        $perPage = $request->input('count', 5);
        $sortOrder = $request->input('order');
        $sortBy = $request->input('sortBy', 'department_name');
    
        // Ensure sort direction is valid
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc'; // or 'desc' if you prefer
        }
    
        $departments = Department::orderBy($sortBy, $sortOrder)->paginate($perPage);
    
        return view('page.school.departments.index', compact('departments'));
    }
    


    public function createDepartment(Request $request)
    {
        $request->validate([
            'department-name' => 'required|string|max:255',
            'department-description' => 'nullable|string',
            'department-code' => 'required|string|max:50',
        ]);


        // Create a new account
        $department = Department::create([
            'department_name' => $request->input('department-name'),
            'department_code' => $request->input('department-code'),
            'department_description' => $request->input('department-description')
        ]);

        $department->save();


        return redirect('Manage/Departments')->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request)
    {
        $request->validate([
            'department-name' => 'required|string|max:255',
            'department-description' => 'nullable|string',
            'department-code' => 'required|string|max:50',
        ]);

        $department = Department::findOrFail($request->input('department_id')); // Use input()

        $department->update([
            'department_name' => $request->input('department-name'), // Match hyphenated names
            'department_description' => $request->input('department-description'),
            'department_code' => $request->input('department-code'),
        ]);

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function deleteDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'confirm-password' => 'required',
        ]);

        // Check if the password is correct
        $account = Account::where('account_id', Auth::id())->first();
        if (!password_verify($request->input('confirm-password'), $account->password)) {
            return redirect()->back()->withErrors(['confirm-password' => 'The provided password is incorrect.']);
        }
        $department = Department::find($request->input('department_id'));
        $department->delete();

        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}
