<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\RFIDLog;
use App\Models\Section;
use App\Models\Department;
use App\Models\YearLevel; // <- added
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\AttendanceClass;



class AttendanceController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $instructorId = $user->account_id;

           // ✅ Get all instructors
        $instructor = Auth::user();

        $perPage = $request->input('count', 5);
        $sortOrder = strtolower($request->input('order', 'desc'));
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';

        $sortFieldMap = [
            'department' => 'departments.department_name',
            'program' => 'programs.program_name',
            'section' => 'sections.section_name',
            'year_level' => 'year_levels.year_level_name', // <- added
            'scanned_at' => 'rfid_logs.scanned_at',
            'first_name' => 'student_records.first_name',
            'last_name' => 'student_records.last_name',
        ];

        $sortByRequest = $request->input('sortBy', 'scanned_at');
        $sortBy = $sortFieldMap[$sortByRequest] ?? 'rfid_logs.scanned_at';

        $filterValue = $request->input('filter');
        $filterBy = $request->input('filterBy');
        $date = $request->input('date');

        // Default load all departments, programs, sections, year levels
        $departments = Department::all();
        $programs = Program::all();
        $sections = Section::all();
        $yearLevels = YearLevel::all(); 

         $query = RFIDLog::query()
            ->join('student_records', 'rfid_logs.record_id', '=', 'student_records.record_id')
            ->leftJoin('departments', 'student_records.department_id', '=', 'departments.department_id')
            ->leftJoin('programs', 'student_records.program_id', '=', 'programs.program_id')
            ->leftJoin('sections', 'student_records.section_id', '=', 'sections.section_id')
            ->leftJoin('year_levels', 'student_records.year_level_id', '=', 'year_levels.year_level_id') 
            ->leftJoin('attendance_classes', function($join) {
                $join->on('student_records.first_name', '=', DB::raw("SUBSTRING_INDEX(attendance_classes.student_name, ',', 1)"))
                     ->on('student_records.last_name', '=', DB::raw("TRIM(SUBSTRING_INDEX(attendance_classes.student_name, ',', -1))"))
                     ->whereColumn('programs.program_name', 'attendance_classes.program')
                     ->whereColumn('sections.section_name', 'attendance_classes.section')
                     ->whereColumn('year_levels.year_level_name', 'attendance_classes.year_level');
            })
            ->select(
                'rfid_logs.*',
                'student_records.first_name',
                'student_records.last_name',
                'departments.department_name',
                'departments.department_id',
                'programs.program_name',
                'programs.program_id',
                'sections.section_name',
                'sections.section_id',
                'year_levels.year_level_name', 
                'year_levels.year_level_id',    
                'attendance_classes.remarks',
                'attendance_classes.instructor'  
            );
        if ($user->role !== 'Admin') {
            $assignments = DB::table('assignments')
                ->where('instructor_id', $instructorId)
                ->get();

            foreach ($assignments as $index => $assignment) {
                Log::info("Assignment #$index", (array) $assignment);
            }

            if ($assignments->isEmpty()) {
                $logs = collect();
                return view('page.school.attendance.index', compact('logs', 'departments', 'programs', 'sections', 'yearLevels'));
            }

            // Filter logs query by assignments: program, section, year level
            $query->where(function ($q) use ($assignments) {
                foreach ($assignments as $assignment) {
                    $q->orWhere(function ($subQ) use ($assignment) {
                        $subQ->where('student_records.program_id', $assignment->program_id);

                        if (!is_null($assignment->section_id)) {
                            $isValidSection = DB::table('sections')
                                ->where('section_id', $assignment->section_id)
                                ->where('program_id', $assignment->program_id)
                                ->exists();

                            if ($isValidSection) {
                                $subQ->where('student_records.section_id', $assignment->section_id);
                            } else {
                                Log::warning("Section ID {$assignment->section_id} does not belong to Program ID {$assignment->program_id}");
                            }
                        }

                        if (!is_null($assignment->year_level_id)) { // <- added
                            $subQ->where('student_records.year_level_id', $assignment->year_level_id);
                        }
                    });
                }
            });

            // Filter departments & programs based on assignments
            $departments = Department::whereIn('department_id', $assignments->pluck('department_id')->filter()->unique())->get();
            $programs = Program::whereIn('program_id', $assignments->pluck('program_id')->filter()->unique())->get();

            // Build valid section IDs from assignments
            $validSectionIds = collect();
            $validYearLevelIds = collect(); // <- added

            foreach ($assignments as $assignment) {
                if (!is_null($assignment->section_id)) {
                    $exists = DB::table('sections')
                        ->where('section_id', $assignment->section_id)
                        ->where('program_id', $assignment->program_id)
                        ->exists();

                    if ($exists) {
                        $validSectionIds->push($assignment->section_id);
                    }
                } else {
                    $programSections = DB::table('sections')
                        ->where('program_id', $assignment->program_id)
                        ->pluck('section_id');

                    $validSectionIds = $validSectionIds->merge($programSections);
                }

                if (!is_null($assignment->year_level_id)) { // <- added
                    $validYearLevelIds->push($assignment->year_level_id);
                }
            }

            $validSectionIds = $validSectionIds->unique();
            $validYearLevelIds = $validYearLevelIds->unique(); // <- added

            // Only load sections and year levels that belong to the assignments/programs
            $sections = Section::whereIn('section_id', $validSectionIds)->get();
            $yearLevels = YearLevel::whereIn('year_level_id', $validYearLevelIds)->get(); // <- added
        }

        // Apply filter dropdown if provided
        if ($filterBy && $filterValue) {
            if (in_array($filterBy, ['department', 'program', 'section', 'year_level'])) { // <- added
                $query->where("student_records.{$filterBy}_id", $filterValue);
            }
        }

        if ($date) {
            $query->whereDate('rfid_logs.scanned_at', $date);
        }

        $query->orderBy($sortBy, $sortOrder);

        $logs = $query->paginate($perPage)->appends($request->query());
         $instructor = Auth::user();

        return view('page.school.attendance.index', compact('logs', 'departments', 'programs', 'sections', 'yearLevels', 'instructor',)); // <- added yearLevels
    }
    public function approve(Request $request)
{
    $request->validate([
        'student_name' => 'required|string',
        'program' => 'required|string',
        'section' => 'required|string',
        'year_level' => 'required|string',
        'date' => 'required|date',
        'time' => 'required',
        'attendance_status' => 'required|string',
        'instructor' => 'required|string',
        'action' => 'required|string',
        'image' => 'nullable|string',
        //  'remarks' => 'required|string',
    ]);

    $account = Auth::user();
        $instructorName = trim("{$account->first_name} {$account->last_name}");

    AttendanceClass::create([
        'student_name'       => $request->student_name,
        'program'            => $request->program,
        'section'            => $request->section,
        'year_level'         => $request->year_level,
        'date'               => $request->date,
        'time'               => $request->time,
        'attendance_status'  => $request->attendance_status,
        'instructor'         => $instructorName,
        'action'             => $request->action,
        'image'              => $request->image,
        // 'remarks'            => $request->remarks,
    ]);

    return back()->with('success', 'Attendance approved and saved!');
}
}
