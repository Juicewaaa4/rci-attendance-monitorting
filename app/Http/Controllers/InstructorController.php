<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceClass;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class InstructorController
{
    /**
     * Show the instructor's attendance records with filters.
     */
    public function record(Request $request)
    {
        // Get the logged-in user
        $account = Auth::user();

        // Build instructor full name
        $instructorName = trim("{$account->first_name} {$account->last_name}");

        // Hidden log IDs stored in session
        $hiddenLogs = session('hidden_instructor_logs', []);

        // Base query
        $query = AttendanceClass::where('instructor', $instructorName)
            ->whereNotIn('id', $hiddenLogs);

        /*
        |---------------------------------------------------------------------------
        | APPLY FILTERS
        |---------------------------------------------------------------------------
        */

        // Search student_name
        if ($request->search) {
            $query->where('student_name', 'LIKE', "%{$request->search}%");
        }

        // Attendance Status filter
        if ($request->status) {
            $query->where('attendance_status', $request->status);
        }

        // Section filter
        if ($request->section) {
            $query->where('section', $request->section);
        }

        // Year Level filter
        if ($request->year_level) {
            $query->where('year_level', $request->year_level);
        }

        // Date filter
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        /*
        |---------------------------------------------------------------------------
        | Execute Query
        |---------------------------------------------------------------------------
        */

        $logs = $query->orderBy('created_at', 'desc')->paginate(10);

        /*
        |---------------------------------------------------------------------------
        | Filter Dropdown Data
        |---------------------------------------------------------------------------
        */

        $sections = AttendanceClass::select('section')->distinct()->pluck('section');
        $yearLevels = AttendanceClass::select('year_level')->distinct()->pluck('year_level');

        return view('page.school.instructors.record', compact('logs', 'sections', 'yearLevels'));
    }

    /**
     * Hide a record for this instructor in the session.
     */
    public function hide(Request $request)
    {
        $logId = $request->input('log_id');

        // Get current hidden array from session
        $hidden = session('hidden_instructor_logs', []);

        // Add the log ID if not already hidden
        if (!in_array($logId, $hidden)) {
            $hidden[] = $logId;
        }

        // Save back to session
        session(['hidden_instructor_logs' => $hidden]);

        return response()->json(['success' => true]);
    }

    /**
     * Export filtered attendance as Excel (fixes ##### issue in Excel and auto-sizes columns).
     */
    public function exportCsv(Request $request)
    {
        $account = Auth::user();
        $instructorName = trim("{$account->first_name} {$account->last_name}");

        $query = AttendanceClass::where('instructor', $instructorName);

        // Apply filters
        if ($request->search) {
            $query->where('student_name', 'LIKE', "%{$request->search}%");
        }
        if ($request->status) {
            $query->where('attendance_status', $request->status);
        }
        if ($request->section) {
            $query->where('section', $request->section);
        }
        if ($request->year_level) {
            $query->where('year_level', $request->year_level);
        }
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        // Create spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $columns = ['ID', 'Student Name', 'Program', 'Section', 'Year Level', 'Date', 'Time', 'Status', 'Instructor', 'Created At', 'Updated At'];
        $sheet->fromArray($columns, NULL, 'A1');

        // Center headers
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Add data rows
        $rowNum = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue("A{$rowNum}", $log->id);
            $sheet->setCellValue("B{$rowNum}", $log->student_name);
            $sheet->setCellValue("C{$rowNum}", $log->program);
            $sheet->setCellValue("D{$rowNum}", $log->section);
            $sheet->setCellValue("E{$rowNum}", $log->year_level);
            $sheet->setCellValue("F{$rowNum}", $log->date);
            $sheet->setCellValue("G{$rowNum}", $log->time);
            $sheet->setCellValue("H{$rowNum}", $log->attendance_status);
            $sheet->setCellValue("I{$rowNum}", $log->instructor);
            $sheet->setCellValue("J{$rowNum}", $log->created_at->format('Y-m-d H:i:s'));
            $sheet->setCellValue("K{$rowNum}", $log->updated_at->format('Y-m-d H:i:s'));

            // Center align all data
            $sheet->getStyle("A{$rowNum}:K{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        // Format Date/Datetime columns to prevent #####
        $sheet->getStyle('F2:F'.$rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);
        $sheet->getStyle('J2:K'.$rowNum)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);

        // Auto-size all columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'attendance_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Write Excel to output
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        $writer->save('php://output');
        exit;
    }
}
