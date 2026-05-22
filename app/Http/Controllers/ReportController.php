<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\RFIDLog;
use App\Models\Section;
use App\Models\YearLevel;
use App\Models\Department;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class ReportController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $instructorId = $user->account_id;

        $ignoreFilters = $request->has('ignore_filters');
        $date = $request->input('date'); // null if not provided

        // Filters from input
        $filters = [
            'department_id' => $request->input('department_id'),
            'program_id' => $request->input('program_id'),
            'year_level_id' => $request->input('year_level_id'),
            'section_id' => $request->input('section_id'),
            'school_year_id' => $request->input('school_year_id'),
        ];

        // Base query with eager loading
        $logsQuery = RFIDLog::with('student.department', 'student.program', 'student.yearLevel', 'student.section', 'student.schoolYear');

        // If not admin, limit to instructor's assignments
        if ($user->role !== 'Admin') {
            $assignments = DB::table('assignments')->where('instructor_id', $instructorId)->get();

            if ($assignments->isEmpty()) {
                // No assignments - no data to show
                $attendance = collect();
                $departments = collect();
                $programs = collect();
                $yearLevels = YearLevel::all(); // can still load all year levels
                $sections = collect();
                $schoolYears = SchoolYear::all(); // can still load all school years

                return view('page.school.reports.index', compact(
                    'attendance',
                    'date',
                    'filters',
                    'departments',
                    'programs',
                    'yearLevels',
                    'sections',
                    'schoolYears'
                ));
            }

            // Collect all assignment IDs for filtering
            $validDepartmentIds = $assignments->pluck('department_id')->filter()->unique()->toArray();
            $validProgramIds = $assignments->pluck('program_id')->filter()->unique()->toArray();

            $validSectionIds = collect();
            foreach ($assignments as $assignment) {
                if (!is_null($assignment->section_id)) {
                    // Verify section belongs to program
                    $exists = DB::table('sections')
                        ->where('section_id', $assignment->section_id)
                        ->where('program_id', $assignment->program_id)
                        ->exists();
                    if ($exists) {
                        $validSectionIds->push($assignment->section_id);
                    }
                } else {
                    // No section assigned means all sections in the program
                    $sectionsForProgram = DB::table('sections')
                        ->where('program_id', $assignment->program_id)
                        ->pluck('section_id');
                    $validSectionIds = $validSectionIds->merge($sectionsForProgram);
                }
            }
            $validSectionIds = $validSectionIds->unique()->toArray();

            // Filter logs query to only student's assignments
            $logsQuery->whereHas('student', function ($q) use ($validDepartmentIds, $validProgramIds, $validSectionIds) {
                $q->whereIn('department_id', $validDepartmentIds)
                  ->whereIn('program_id', $validProgramIds)
                  ->whereIn('section_id', $validSectionIds);
            });

            // Filter dropdown data accordingly
            $departments = Department::whereIn('department_id', $validDepartmentIds)->get();
            $programs = Program::whereIn('program_id', $validProgramIds)->get();
            $sections = Section::whereIn('section_id', $validSectionIds)->get();
        } else {
            // Admin sees all
            $departments = Department::all();
            $programs = Program::all();
            $sections = Section::all();
        }

        // YearLevels and SchoolYears are usually global filters
        $yearLevels = YearLevel::all();
        $schoolYears = SchoolYear::all();

        // Apply date filter if not ignored and provided
        if (!$ignoreFilters && $date) {
            $logsQuery->whereDate('scanned_at', $date);
        }

        // Apply other filters if not ignored
        if (!$ignoreFilters) {
            foreach ($filters as $field => $value) {
                if ($value) {
                    $logsQuery->whereHas('student', function ($q) use ($field, $value) {
                        $q->where($field, $value);
                    });
                }
            }
        }

        $logs = $logsQuery->get();

        $attendance = $logs->groupBy(function ($log) {
            $s = $log->student;
            return implode('-', [
                $s->department_id,
                $s->program_id,
                $s->year_level_id,
                $s->section_id,
                $s->school_year_id,
                $log->scanned_at->format('Y-m-d'),
            ]);
        })->map(function ($logs) {
            $student = $logs->first()->student;

            $distinctStudents = $logs->pluck('record_id')->unique()->count();

            // Safely handle section being null
            $totalStudents = $student->section
                ? $student->section->studentRecords()->where('school_year_id', $student->school_year_id)->count()
                : 0;

            return [
                'department' => $student->department->department_name ?? 'N/A',
                'program' => $student->program->program_name ?? 'N/A',
                'year_level' => $student->yearLevel->year_level_name ?? 'N/A',
                'section' => $student->section->section_name ?? 'N/A',
                'school_year' => $student->schoolYear->school_year_name ?? 'N/A',
                'present' => $distinctStudents,
                'total' => $totalStudents,
                'dates' => $logs->pluck('scanned_at')->map(function ($date) {
                    return $date->format('Y-m-d');
                })->unique(),
            ];
        });

        return view('page.school.reports.index', compact(
            'attendance',
            'date',
            'filters',
            'departments',
            'programs',
            'yearLevels',
            'sections',
            'schoolYears'
        ));
    }
}
