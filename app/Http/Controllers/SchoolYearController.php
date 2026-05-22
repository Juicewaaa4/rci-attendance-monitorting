<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SchoolYearController
{


    public function index(Request $request)
    {
        $perPage = $request->input('count', 5);
        $sortOrder = $request->input('order');
        $sortBy = $request->input('sortBy', 'school_year_name');
    
        // Ensure sort direction is valid
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc'; // or 'desc' if you prefer
        }
    
        $schoolyears = SchoolYear::orderBy($sortBy, $sortOrder)->paginate($perPage);
    
        return view('page.school.school-year.index', compact('schoolyears'));
    }

    public function createSchoolYear(Request $request)
    {
        $request->validate([
            'school-year-name' => 'required|string|max:255',
            'school-year-description' => 'nullable|string',
            'school-year-code' => 'required|string|max:50|regex:/^[a-zA-Z0-9]+$/',
        ]);


        // Create a new account
        $schoolYear = SchoolYear::create([
            'school_year_name' => $request->input('school-year-name'),
            'school_year_description' => $request->input('school-year-description'),
            'school_year_code' => $request->input('school-year-code')
        ]);

        $schoolYear->save();

        return redirect()->back()->with('success', 'School Year created successfully.');
    }
    public function updateSchoolYear(Request $request)
    {
        $request->validate([
            'school-year-name' => 'required|string|max:255',
            'school-year-description' => 'nullable|string',
            'school-year-code' => 'required|string|max:50|regex:/^[a-zA-Z0-9]+$/',
        ]);

        $schoolYear = SchoolYear::findOrFail($request->input('school-year-id')); // Use input()

        if (!$schoolYear) {
            return redirect()->back()->withErrors(['school-year-id' => 'School Year not found.']);
        }

        $schoolYear->update([
            'school_year_name' => $request->input('school-year-name'),
            'school_year_description' => $request->input('school-year-description'),
            'school_year_code' => $request->input('school-year-code')
        ]);

        return redirect()->back()->with('success', 'School Year updated successfully.');
    }

    public function deleteSchoolYear(Request $request)
    {
        $request->validate([
            'school-year-id' => 'required',
            'confirm-password' => 'required',
        ]);

        // Check if the password is correct
        $account = Account::where('account_id', Auth::id())->first();
        if (!password_verify($request->input('confirm-password'), $account->password)) {
            return redirect()->back()->withErrors(['confirm-password' => 'The provided password is incorrect.']);
        }
        $schoolYear = SchoolYear::find($request->input('school-year-id'));
        $schoolYear->delete();

        return redirect()->back()->with('success', 'School Year deleted successfully.');
    }
}
