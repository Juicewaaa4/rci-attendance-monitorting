<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\YearLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YearLevelController
{

    public function index(Request $request)
    {
        $perPage = $request->input('count', 5); // Default to 5 items per page
        $sortOrder = $request->input('order', 'asc'); // Default to ascending order
        $sortBy = $request->input('sortBy', 'year_level_name'); // Default to program_name
    
        // Ensure sort direction is valid
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc'; // Default to 'asc' if invalid
        }
    
        // Modify the query to join the Department table if sorting by department_name
        $yearlevels = YearLevel::query();
    
        // If sorting by department_name, join with the department table
        if ($sortBy == 'department_name') {
            $yearlevels->join('departments', 'year_levels.department_id', '=', 'departments.department_id')
                ->orderBy('departments.department_name', $sortOrder);
        } else {
            $yearlevels->orderBy($sortBy, $sortOrder);
        }
    
        // Paginate the results
        $yearlevels = $yearlevels->paginate($perPage);
    
        return view('page.school.year-levels.index', compact('yearlevels'));
    }

    public function createYearLevel(Request $request)
    {

        $request->validate([
            'year-level-name' => 'required|string|max:255|regex:/^[A-Za-z0-9\s]+$/',
            'year-level-description' => 'nullable|max:255',
            'department-id' => 'nullable|exists:departments,department_id',
            'year-level-code' => 'required|string|max:50',
        ]);

        // Create a new account
        $yearlevel = YearLevel::create([
            'year_level_name' => $request->input('year-level-name'),
            'year_level_description' => $request->input('year-level-description'),
            'department_id' => $request->input('department-id'),
            'year_level_code' => $request->input('year-level-code')
        ]);

        $yearlevel->save();

        return redirect()->back()->with('success', 'Year Level created successfully.');
    }

    public function updateYearLevel(Request $request)
    {
        $request->validate([
            'year-level-name' => 'required|string|max:255|regex:/^[A-Za-z0-9\s]+$/',
            'year-level-description' => 'nullable|max:255',
            'department-id' => 'nullable|exists:departments,department_id',

        ]);

        $yearlevel = YearLevel::findOrFail($request->input('year-level-id'));

        $yearlevel->update([
            'year_level_name' => $request->input('year-level-name'),
            'year_level_description' => $request->input('year-level-description'),
            'department_id' => $request->input('department-id')
        ]);

        return redirect()->back()->with('success', 'Year Level updated successfully.');
    }

    public function deleteYearLevel(Request $request)
    {
        $request->validate([
            'year-level-id' => 'required|exists:year_levels,year_Level_id',
            'confirm-password' => 'required',
        ]);

        // Check if the password is correct
        $account = Account::where('account_id', Auth::id())->first();
        if (!password_verify($request->input('confirm-password'), $account->password)) {
            return redirect()->back()->withErrors(['confirm-password' => 'The provided password is incorrect.']);
        }
        $yearlevel = YearLevel::find($request->input('year-level-id'));
        $yearlevel->delete();

        return redirect()->back()->with('success', 'Year Level deleted successfully.');
    }
}
