<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProgramController
{

    public function index(Request $request)
    {
        $perPage = $request->input('count', 5); // Default to 5 items per page
        $sortOrder = $request->input('order', 'asc'); // Default to ascending order
        $sortBy = $request->input('sortBy', 'program_name'); // Default to program_name
    
        // Ensure sort direction is valid
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc'; // Default to 'asc' if invalid
        }
    
        // Modify the query to join the Department table if sorting by department_name
        $programs = Program::query();
    
        // If sorting by department_name, join with the department table
        if ($sortBy == 'department_name') {
            $programs->join('departments', 'programs.department_id', '=', 'departments.department_id')
                ->orderBy('departments.department_name', $sortOrder);
        } else {
            $programs->orderBy($sortBy, $sortOrder);
        }
    
        // Paginate the results
        $programs = $programs->paginate($perPage);
    
        return view('page.school.programs.index', compact('programs'));
    }
    
    
    public function createProgram(Request $request)
    {

        $request->validate([
            'program-name' => 'required|string|max:255',
            'program-code' => 'required|max:50',
            'program-description' => 'nullable|max:255',
            'department-id' => 'nullable|exists:departments,department_id',
        ]);

        // Create a new account
        $program = Program::create([
            'program_name' => $request->input('program-name'),
            'program_code' => $request->input('program-code'),
            'program_description' => $request->input('program-description'),
            'department_id' => $request->input('department-id')
        ]);

        $program->save();

        return redirect('Manage/Programs')->with('success', 'Program created successfully.');
    }

    public function updateProgram(Request $request)
    {
        $request->validate([
            'program-name' => 'required|string|max:255',
            'program-code' => 'required|max:50',
            'program-description' => 'nullable|max:255',
            'department-id' => 'nullable|exists:departments,department_id',
        ]);

        $program = Program::findOrFail($request->input('program_id'));    

        $program->update([
            'program_name' => $request->input('program-name'),
            'program_code' => $request->input('program-code'),
            'program_description' => $request->input('program-description'),
            'department_id' => $request->input('department-id')
        ]);

        return redirect()->back()->with('success', 'Program updated successfully.');
    }

    public function deleteProgram(Request $request){
        $request->validate([
            'program_id' => 'required|exists:programs,program_id',
            'confirm-password' => 'required',
        ]);
    
        // Check if the password is correct
        $account = Account::where('account_id', Auth::id())->first();
        if (!password_verify($request->input('confirm-password'), $account->password)) {
            return redirect()->back()->withErrors(['confirm-password' => 'The provided password is incorrect.']);
        }
        $program = Program::find($request->input('program_id'));
        $program->delete();

        return redirect()->back()->with('success', 'Program deleted successfully.');
    }
}
