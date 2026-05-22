<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SectionController
{
    public function index(Request $request)
    {
        $perPage = $request->input('count', 5); // Default to 5 items per page
        $sortOrder = $request->input('order', 'asc'); // Default to ascending order
        $sortBy = $request->input('sortBy', 'section_name'); // Default sort column
    
        // Ensure the sort direction is valid
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }
    
        $sections = Section::query()
            ->with(['program', 'department', 'yearLevel', 'schoolYear']); // eager load relationships
    
        // Join and sort logic
        switch ($sortBy) {
            case 'program_name':
                $sections->join('programs', 'sections.program_id', '=', 'programs.program_id')
                         ->orderBy('programs.program_name', $sortOrder);
                break;
    
            case 'department_name':
                $sections->join('departments', 'sections.department_id', '=', 'departments.department_id')
                         ->orderBy('departments.department_name', $sortOrder);
                break;
    
            case 'year_level_name':
                $sections->join('year_levels', 'sections.year_level_id', '=', 'year_levels.year_level_id')
                         ->orderBy('year_levels.year_level_name', $sortOrder);
                break;
    
            case 'school_year_name':
                $sections->join('school_years', 'sections.school_year_id', '=', 'school_years.school_year_id')
                         ->orderBy('school_years.school_year_name', $sortOrder);
                break;
    
            default:
                $sections->orderBy($sortBy, $sortOrder);
                break;
        }
    
        // Avoid selecting ambiguous columns after joins
        $sections->select('sections.*');
    
        $sections = $sections->paginate($perPage);
    
        return view('page.school.sections.index', compact('sections'));
    }
    
    
    public function createSection(Request $request)
    {

        $request->validate([
            'section-name' => 'required|string|max:255',
            'section-description' => 'nullable|max:255',
            'section-code' => 'required|string|max:255',
            'program-id' => 'nullable|exists:programs,program_id',
            'department-id' => 'nullable|exists:departments,department_id',
            'year-level-id' => 'nullable|exists:year_levels,year_level_id',
        ]);

        // Create a new account
        $section = Section::create([
            'section_name' => $request->input('section-name'),
            'section_description' => $request->input('section-description'),
            'section_code' => $request->input('section-code'),
            'program_id' => $request->input('program-id'),
            'department_id' => $request->input('department-id'),
            'year_level_id' => $request->input('year-level-id'),
        ]);

        $section->save();

        return redirect()->back()->with('success', 'Section created successfully.');
    }

    public function updateSection(Request $request)
    {
        $request->validate([
            'section-name' => 'required|string|max:255',
            'section-description' => 'nullable|max:255',
            'section-code' => 'required|string|max:255',
            'program-id' => 'nullable|exists:programs,program_id',
            'department-id' => 'nullable|exists:departments,department_id',
            'year-level-id' => 'nullable|exists:year_levels,year_level_id',
        ]);

        // Find the section by ID
        $section = Section::findOrFail($request->input('section_id'));

        // Update the section details
        $section->update([
            'section_name' => $request->input('section-name'),
            'section_description' => $request->input('section-description'),
            'section_code' => $request->input('section-code'),
            'program_id' => $request->input('program-id'),
            'department_id' => $request->input('department-id'),
            'year_level_id' => $request->input('year-level-id'),
        ]);

        return redirect()->back()->with('success', 'Section updated successfully.');
    }

    public function deleteSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,section_id',
            'confirm-password' => 'required',
        ]);

        // Check if the password is correct
        $account = Account::where('account_id', Auth::id())->first();
        if (!password_verify($request->input('confirm-password'), $account->password)) {
            return redirect()->back()->withErrors(['confirm-password' => 'The provided password is incorrect.']);
        }
        $section = Section::find($request->input('section_id'));
        $section->delete();

        return redirect()->back()->with('success', 'Section deleted successfully.');
    }
}
