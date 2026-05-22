<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Program;
use App\Models\Section;
use App\Models\Assignment;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\YearLevel;


class AccountController
{
    public function index(Request $request)
    {
        $perPage = $request->input('count', 5);
        $sortOrder = in_array($request->input('order', 'desc'), ['asc', 'desc']) ? $request->input('order') : 'desc';
        $sortBy = $request->input('sortBy', 'last_name');

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query = Account::from('accounts')
            ->where('accounts.role', 'instructor');

        if (in_array($sortBy, ['last_name', 'first_name', 'middle_name'])) {
            $query->orderBy("accounts.$sortBy", $sortOrder);
        } elseif ($sortBy === 'department') {
            $query
                ->leftJoin('assignments', 'accounts.account_id', '=', 'assignments.instructor_id')
                ->leftJoin('departments', 'assignments.department_id', '=', 'departments.department_id')
                ->select('accounts.account_id', 'accounts.first_name', 'accounts.middle_name', 'accounts.last_name', 'accounts.role', 'accounts.email')
                ->groupBy('accounts.account_id', 'accounts.first_name', 'accounts.middle_name', 'accounts.last_name', 'accounts.role', 'accounts.email')
                ->orderBy(
                    Department::select('department_name')
                        ->whereColumn('departments.department_id', 'assignments.department_id')
                        ->limit(1),
                    $sortOrder
                );
        } elseif ($sortBy === 'program') {
            $query
                ->leftJoin('assignments', 'accounts.account_id', '=', 'assignments.instructor_id')
                ->leftJoin('programs', 'assignments.program_id', '=', 'programs.program_id')
                ->select('accounts.account_id', 'accounts.first_name', 'accounts.middle_name', 'accounts.last_name', 'accounts.role', 'accounts.email')
                ->groupBy('accounts.account_id', 'accounts.first_name', 'accounts.middle_name', 'accounts.last_name', 'accounts.role', 'accounts.email')
                ->orderBy(
                    Program::select('program_name')
                        ->whereColumn('programs.program_id', 'assignments.program_id')
                        ->limit(1),
                    $sortOrder
                );
        } elseif ($sortBy === 'section') {
            $query
                ->leftJoin('assignments', 'accounts.account_id', '=', 'assignments.instructor_id')
                ->leftJoin('sections', 'assignments.section_id', '=', 'sections.section_id')
                ->select('accounts.account_id', 'accounts.first_name', 'accounts.middle_name', 'accounts.last_name', 'accounts.role', 'accounts.email')
                ->groupBy('accounts.account_id', 'accounts.first_name', 'accounts.middle_name', 'accounts.last_name', 'accounts.role', 'accounts.email')
                ->orderBy(
                    Section::select('section_name')
                        ->whereColumn('sections.section_id', 'assignments.section_id')
                        ->limit(1),
                    $sortOrder
                );
        }

        $instructors = $query->paginate($perPage)->appends($request->query());

        return view('page.school.instructors.index', compact('instructors'));
    }

   public function showCreateForm()
{
    $departments = Department::all();
    $programs = Program::all();
    $sections = Section::select('section_id', 'section_name', 'program_id', 'year_level_id')
    ->orderByRaw("CAST(SUBSTRING(section_name, 9) AS UNSIGNED) ASC") // works for names like "Section 1"
    ->get();

    $yearLevels = YearLevel::orderByRaw("CAST(SUBSTRING(year_level_code, 3) AS UNSIGNED) ASC")->get();


    return view('page.school.instructors.create', compact('departments', 'programs', 'sections', 'yearLevels'));
}



    public function loadAccount($account_id)
    {
        $account = Account::with(['assignments'])->findOrFail($account_id);


        $sections = Section::all();
        $programs = Program::all();
        $departments = Department::all();
        $selectedSections = old('section_ids', $account->assignments->pluck('section_id')->filter()->toArray());

        return view('page.school.instructors.edit', compact('account', 'programs', 'departments', 'sections'));
    }

    public function createAccount(Request $request)
    {
        // Validate user inputs
        $request->validate([
            'first-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'middle-name' => ['nullable', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'last-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'email' => ['required', 'email', 'unique:accounts,email', 'regex:/^[a-zA-Z][a-zA-Z0-9._-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'department-id' => 'nullable|exists:departments,department_id',
            'program-id' => 'nullable|exists:programs,program_id',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'exists:sections,section_id',
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'min:8'],
        ]);

        // Create a new account
        $account = Account::create([
            'first_name' => $request->input('first-name'),
            'middle_name' => $request->input('middle-name'),
            'last_name' => $request->input('last-name'),
            'role' => 'instructor',
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            

        ]);

        // Create multiple assignments for each selected section
        
$sectionIds = $request->input('section_ids', []);
foreach ($sectionIds as $sectionId) {
    $section = Section::find($sectionId); // get section with program & dept
    
    Assignment::create([
        'instructor_id' => $account->account_id,
        'section_id' => $sectionId,
        'year_level_id' => $section->year_level_id,   // from section
        'program_id'    => $section->program_id,      // from section
        'department_id' => $section->department_id,   // from section
    ]);
}


        $account->save();

        cache()->forget('instructors'); // Clear the cache for accounts    
        cache()->forget('assignments');

        return back()->with('success', 'Account created successfully.');
    }

    public function updateAccountOwnInfo(Request $request)
    {
        // Validate user inputs
        $request->validate([
            'first-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'middle-name' => ['nullable', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'last-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'password_confirmation' => ['required'], // Added validation
            'password' => ['required'],
        ]);

        // Get authenticated user
        $account = Account::where('account_id', Auth::id())->first();

        // Check if the entered current password matches the stored password
        if (!Hash::check($request->input('password_confirmation'), $account->password)) {
            return back()->withErrors(['password_confirmation' => 'The current password is incorrect.']);
        }

        // Update account details
        $account->first_name = $request->input('first-name');
        $account->middle_name = $request->input('middle-name');
        $account->last_name = $request->input('last-name');

        // Save changes
        $account->update();

        return back()->with('success', 'Account information updated successfully.');
    }

    public function updateAccountInfo(Request $request)
    {
        // Validate user inputs
        $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'first-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'middle-name' => ['nullable', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'last-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'department-id' => 'nullable|exists:departments,department_id',
            'program-id' => 'nullable|exists:programs,program_id',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'exists:sections,section_id',
            
        ]);

        // Find the account
        $account = Account::findOrFail($request->input('account_id'));

        // Update account details
        $account->update([
            'first_name' => $request->input('first-name'),
            'middle_name' => $request->input('middle-name'),
            'last_name' => $request->input('last-name'),
        ]);

        // Only update assignments if section_ids is present in the request
        if ($request->has('section_ids')) {
            Assignment::where('instructor_id', $account->account_id)->delete();

            $sectionIds = $request->input('section_ids', []);
            foreach ($sectionIds as $sectionId) {
                Assignment::create([
                    'instructor_id' => $account->account_id,
                    'section_id' => $sectionId,
                    'program_id' => $request->input('program-id'),
                    'department_id' => $request->input('department-id'),
                ]);
            }
        }
        cache()->forget('instructors');
        cache()->forget('assignments');

        return back()->with('success', 'Instructor account updated successfully.');
    }
    public function confirmPassword(Request $request)
    {

        $request->validate([
            'current-password' => ['required'],
        ]);

        $account = Account::where('account_id', Auth::id())->first();

        // Check if the entered current password matches the stored password
        if (!Hash::check($request->input('current-password'), $account->password)) {
            return back()->withErrors(['current-password' => 'The current password is incorrect.']);
        }

        return redirect('/Change/Password')->with('success', 'You can now change your password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (Auth::check()) {
            // Authenticated User: Change their own password
            $account = Auth::user();
        } else {
            // Recovery Mode: Get account using recovery_email from session
            $recoveryEmail = session('recovery_email');
            $account = Account::where('email', $recoveryEmail)->first();

            if (!$account) {
                return redirect('/Login')->withErrors(['error' => 'Account not found.']);
            }

            // Remove stored email after updating password
            session()->forget('recovery_email');
        }

        // Update password securely
        $account->update([
            'password' => Hash::make($request->input('password'))
        ]);

        // Redirect based on authentication status
        return Auth::check()
            ? redirect('/Edit/Account')->with('success', 'Password changed successfully.')
            : redirect('/Login')->with('success', 'Password changed successfully.');
    }

    public function deleteInstructor(Request $request)
    {
        $request->validate([
            'instructor-id' => 'required|exists:accounts,account_id',
            'confirm-password' => 'required',
        ]);

        // Check if the password is correct
        $account = Account::where('account_id', Auth::id())->first();
        if (!password_verify($request->input('confirm-password'), $account->password)) {
            return redirect()->back()->withErrors(['confirm-password' => 'The provided password is incorrect.']);
        }

        $instructor = Account::find($request->input('instructor-id'));
        $instructor->delete();

        cache()->forget('instructors');

        return redirect()->back()->with('success', 'Instructor deleted successfully.');
    }
}
