<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Program;
use App\Models\Section;
use App\Models\YearLevel;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class StudentController
{

    public function index(Request $request)
    {
        $perPage = $request->input('count', 5);
        $sortOrder = $request->input('order', 'asc');
        $sortBy = $request->input('sortBy', 'last_name');

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }


        $students = StudentRecord::query()->with(['section', 'program', 'department', 'yearLevel', 'schoolYear']);

        // Sorting logic
        switch ($sortBy) {
            case 'program_name':
                $students->leftJoin('programs', 'student_records.program_id', '=', 'programs.program_id')
                    ->orderBy('programs.program_name', $sortOrder)
                    ->addSelect('programs.program_name as program_name'); // Alias to avoid ambiguity
                break;

            case 'department_name':
                $students->leftJoin('departments', 'student_records.department_id', '=', 'departments.department_id')
                    ->orderBy('departments.department_name', $sortOrder)
                    ->addSelect('departments.department_name as department_name'); // Alias to avoid ambiguity
                break;

            case 'year_level_name':
                $students->leftJoin('year_levels', 'student_records.year_level_id', '=', 'year_levels.year_level_id')
                    ->orderBy('year_levels.year_level_name', $sortOrder)
                    ->addSelect('year_levels.year_level_name as year_level_name'); // Alias to avoid ambiguity
                break;

            case 'school_year_name':
                $students->leftJoin('school_years', 'student_records.school_year_id', '=', 'school_years.school_year_id')
                    ->orderBy('school_years.school_year_name', $sortOrder)
                    ->addSelect('school_years.school_year_name as school_year_name'); // Alias to avoid ambiguity
                break;

            case 'student_id': // Default sorting by student_id value (numerically) and length as a secondary criterion
                $students->orderByRaw('CAST(student_id AS UNSIGNED) ' . $sortOrder) // Numeric sorting of student_id
                    ->orderByRaw('LENGTH(student_id) ' . $sortOrder); // Then by the length of student_id
                break;
            default:
                $students->orderBy($sortBy, $sortOrder);
        }

        $students->select('student_records.*'); // Avoid ambiguous column errors

        $students = $students->paginate($perPage)->appends($request->query());

        return view('page.school.students.index', compact('students'));
    }


public function createStudentRecord(Request $request)
{
    $request->validate([
        'first_name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
        'middle_name' => ['nullable', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
        'last_name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
        'student_id' => 'required|unique:student_records,student_id',
        'rfid' => 'nullable|unique:student_records,rfid',
        'department_id' => 'nullable|exists:departments,department_id',
        'program_id' => 'nullable|exists:programs,program_id',
        'year_level_id' => 'nullable|exists:year_levels,year_level_id',
        'school_year_id' => 'nullable|exists:school_years,school_year_id',
        'section_id' => 'nullable|exists:sections,section_id',
    ], [
        'student_id.unique' => 'This student number is already registered.',
        'rfid.unique'       => 'This RFID is already registered.',
    ]);

    // 🔹 EXTRA DUPLICATE CHECK (based on name)
    $duplicate = StudentRecord::where('first_name', $request->first_name)
        ->where('middle_name', $request->middle_name ?? '')
        ->where('last_name', $request->last_name)
        ->first();

    if ($duplicate) {
    return redirect()->back()->with('error', 'This student already exists in the system.');
}


    // ✅ If no duplicate, create the record
    $studentRecord = StudentRecord::create([
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
        'student_id' => $request->student_id,
        'department_id' => $request->department_id,
        'program_id' => $request->program_id,
        'year_level_id' => $request->year_level_id,
        'school_year_id' => $request->school_year_id,
        'section_id' => $request->section_id,
        'status' => 'active',
        'rfid' => $request->rfid,
    ]);

    if ($request->filled('face_image')) {
        $data = $request->input('face_image');
        $type = 'png';

        if (preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($matches[1]);
            if (!in_array($type, ['jpg','jpeg','gif','png'])) $type = 'png';
        }

        $data = base64_decode($data);

        if ($data !== false) {
            $folder = public_path('images/faces');
            if (!is_dir($folder)) mkdir($folder, 0755, true);

            $filename = 'images/faces/' . time() . '_' . uniqid() . '.' . $type;
            $path = public_path($filename);
            file_put_contents($path, $data);

            $studentRecord->face_image = $filename;

            // === Face encoding with fixed Python path ===
            $pythonPath = '"C:\\Program Files\\Python311\\python.exe"';
$scriptPath = '"' . base_path('scripts/generate_encoding.py') . '"';
$imagePath = '"images/faces/' . basename($path) . '"';  // ✅ pass relative path


            $command = "$pythonPath $scriptPath $imagePath";

            \Log::info('Python command: ' . $command);

            $output = shell_exec($command);
            \Log::info('Python raw output: ' . $output);

            $result = json_decode($output, true);

            if ($result === null) {
                \Log::error('Failed to decode JSON from Python output. Raw output: ' . $output);
            } elseif (isset($result['encoding'])) {
                $studentRecord->face_encoding = json_encode($result['encoding']);
                \Log::info('Face encoding saved successfully for student ID: ' . $studentRecord->student_id);
            } else {
                \Log::error('Face encoding failed for student ID: ' . $studentRecord->student_id . ' Error: ' . ($result['error'] ?? 'Unknown'));
            }
            // ===========================================
        }
    }

    $studentRecord->save();
    cache()->forget('students');

    return back()->with('success', 'Record created successfully.');
}





    public function updateStudentRecord(Request $request)
    {
        // Find the existing record
        $studentRecord = StudentRecord::find($request->input('record_id'));

        if (!$studentRecord) {
            return back()->withErrors(['error' => 'Record not found.']);
        }

        // Validate user inputs
        $request->validate([
            'first-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'middle-name' => ['nullable', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'last-name' => ['required', 'max:55', 'regex:/^[A-Za-z\s]+$/'],
            'student-id' => [
                'required',
                Rule::unique('student_records', 'student_id')->ignore($studentRecord->student_id, 'student_id')
            ],
            'rfid' =>  [
                'required',
                Rule::unique('student_records', 'rfid')->ignore($studentRecord->rfid, 'rfid')
            ],
            'department-id' => 'nullable|exists:departments,department_id',
            'program-id' => 'nullable|exists:programs,program_id',
            'year-level-id' => 'nullable|exists:year_levels,year_level_id',
            'section-id' => 'nullable|exists:sections,section_id',
            'school-year-id' => 'nullable|exists:school_years,school_year_id',
        ]);

        // Update the account
        $studentRecord->update([
            'first_name' => $request->input('first-name'),
            'middle_name' => $request->input('middle-name'),
            'last_name' => $request->input('last-name'),
            'student_id' => $request->input('student-id'),
            'department_id' => $request->input('department-id'),
            'program_id' => $request->input('program-id'),
            'year_level_id' => $request->input('year-level-id'),
            'section_id' => $request->input('section-id'),
            'school_year_id' => $request->input('school-year-id'),
            'rfid' => $request->input('rfid'),
        ]);

        cache()->forget('students');

        return back()->with('success', 'Record updated successfully.');
    }

    public function deleteStudentRecord(Request $request)
    {
        $request->validate([
            'record_id' => 'required|exists:student_records,record_id',
            'confirm-password' => 'required',
        ]);

        // Check if the password is correct
        $account = Account::where('account_id', Auth::id())->first();
        if (!password_verify($request->input('confirm-password'), $account->password)) {
            return redirect()->back()->withErrors(['confirm-password' => 'The provided password is incorrect.']);
        }

        $studentRecord = StudentRecord::find($request->input('record_id'));
        $studentRecord->delete();

        cache()->forget('students');

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }

    public function importStudentRecord(Request $request)
    {
        $request->validate([
            'upload_file' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('upload_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray();

        $duplicateErrors = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip headers
            if (!isset($row[3]) || empty($row[3])) continue; // Skip if no Student ID

            // Check for duplicate student ID
            $existingStudent = StudentRecord::where('student_id', $row[3])->first();
            if ($existingStudent) {
                $duplicateErrors[] = $row[3]; // Add duplicate ID to error array
                continue; // Skip this row and continue with the next
            }

            // Get related IDs from each code
            $yearLevelId = \App\Models\YearLevel::where('year_level_code', $row[7] ?? null)->value('year_level_id');
            $departmentId = \App\Models\Department::where('department_code', $row[5] ?? null)->value('department_id');
            $programId = \App\Models\Program::where('program_code', $row[6] ?? null)->value('program_id');
            $sectionId = \App\Models\Section::where('section_code', $row[9] ?? null)->value('section_id');
            $schoolYearId = \App\Models\SchoolYear::where('school_year_code', $row[8] ?? null)->value('school_year_id');

            // Handle RFID (Row 4) - Ignore if empty
            $rfid = isset($row[4]) && !empty($row[4]) ? $row[4] : null;

            // Create or update the student record
            StudentRecord::updateOrCreate(
                ['student_id' => $row[3]],
                [
                    'last_name'      => $row[0] ?? '',
                    'first_name'     => $row[1] ?? '',
                    'middle_name'    => $row[2] ?? '',
                    'rfid'           => $rfid,
                    'year_level_id'  => $yearLevelId,
                    'department_id'  => $departmentId,
                    'program_id'     => $programId,
                    'section_id'     => $sectionId,
                    'school_year_id' => $schoolYearId,
                ]
            );
        }

        // Handle duplicates
        if (!empty($duplicateErrors)) {
            return redirect()->back()->with(['duplicates' => $duplicateErrors]);
        }

        cache()->forget('students');

        return redirect()->back()->with('success', 'Students uploaded successfully!');
    }
    public function getPrograms($department_id)
    {
        $programs = Program::where('department_id', $department_id)
            ->select('program_id', 'program_name')  // select only the needed columns
            ->get();

        return response()->json($programs);
    }

    public function getSections($program_id)
    {
        $sections = Section::whereHas('yearLevel', function ($query) use ($program_id) {
            $query->where('program_id', $program_id);
        })->select('section_id', 'section_name')->get();

        return response()->json($sections);
    }

    public function getYearLevels($department_id)
    {
        $yearLevels = YearLevel::where('department_id', $department_id)
            ->select('year_level_id', 'year_level_name')
            ->get();

        return response()->json($yearLevels);
    }
}
