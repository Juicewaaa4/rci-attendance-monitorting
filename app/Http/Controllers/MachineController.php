<?php

namespace App\Http\Controllers;

use App\Models\RFIDLog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use Carbon\Carbon;

class MachineController
{
    public function loginRFID(Request $request)
{
    $rfid = $request->input('rfid');
    $imageData = $request->input('image'); // Base64 image from webcam after RFID scan

    // Find student
    $student = StudentRecord::with(['section', 'yearLevel', 'schoolYear', 'program', 'department'])
        ->where('rfid', $rfid)
        ->first();

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid/Unregistered RFID. Please try again.',
        ]);
    }

    // Ensure webcam image is provided
    if (!$imageData) {
        return response()->json([
            'success' => false,
            'message' => 'Facial image not provided. Please look at the camera.',
        ]);
    }

    // Save the live captured image temporarily
    $capturedImage = str_replace('data:image/png;base64,', '', $imageData);
    $capturedImage = str_replace(' ', '+', $capturedImage);
    $capturedImageName = 'captured_' . Str::uuid() . '.png';
    $capturedImagePath = public_path('rfid_images/' . $capturedImageName);
    file_put_contents($capturedImagePath, base64_decode($capturedImage));

    // Fetch precomputed face encoding from DB
    $storedEncoding = $student->face_encoding;
    if (!$storedEncoding) {
        if (file_exists($capturedImagePath)) unlink($capturedImagePath);
        return response()->json([
            'success' => false,
            'message' => 'Face encoding not found. Contact admin.',
        ]);
    }

    // Compare captured face with precomputed encoding
    $match = $this->compareFacesWithEncoding($capturedImagePath, $storedEncoding);

    if (!$match) {
        if (file_exists($capturedImagePath)) unlink($capturedImagePath);
        return response()->json([
            'success' => false,
            'message' => 'Face verification failed. RFID and face do not match.',
        ]);
    }

    // Latest attendance log
    $latestLog = RFIDLog::where('record_id', $student->record_id)
        ->orderBy('scanned_at', 'desc')
        ->first();

    // Determine action considering date change
    if ($latestLog) {
        $lastLogDate = Carbon::parse($latestLog->scanned_at)->toDateString();
        $todayDate = now()->toDateString();

        if ($lastLogDate !== $todayDate) {
            // New day → always start with Log-in
            $action = 'Log-in';
        } else {
            // Same day → alternate
            $action = ($latestLog->action === 'Log-in') ? 'Log-out' : 'Log-in';
        }
    } else {
        // No previous log → Log-in
        $action = 'Log-in';
    }

    // Time restriction checks (1 minute cooldown)
    if ($latestLog) {
        $scannedTime = Carbon::parse($latestLog->scanned_at);
        if ($latestLog->action === 'Log-out' && $scannedTime->diffInMinutes(now()) < 1 && $action === 'Log-in') {
            return response()->json([
                'success' => false,
                'message' => 'Please wait 1 minute before logging in again.',
            ]);
        }
        if ($latestLog->action === 'Log-in' && $scannedTime->diffInMinutes(now()) < 1 && $action === 'Log-out') {
            return response()->json([
                'success' => false,
                'message' => 'Please wait 1 minute before logging out.',
            ]);
        }
    }

    // Save captured image as attendance proof
    $attendanceImageName = $action . '_' . Str::uuid() . '.png';
    $attendanceImagePath = 'rfid_images/' . $attendanceImageName;
    rename($capturedImagePath, public_path($attendanceImagePath)); // Move temp image

    // Save new attendance log
    RFIDLog::create([
        'record_id' => $student->record_id,
        'rfid' => $rfid,
        'action' => $action,
        'image' => $attendanceImagePath,
        'scanned_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => $action . ' successful!',
        'student' => [
            'record_id' => $student->record_id,
            'rfid' => $student->rfid,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'course' => $student->course,
            'year_level' => $student->yearLevel->name ?? $student->year_level,
            'section' => $student->section->name ?? $student->section,
            'school_year' => $student->schoolYear->name ?? null,
            'program' => $student->program->name ?? null,
            'department' => $student->department->name ?? null,
            'face_image' => $student->face_image,
        ],
        'last_log' => $latestLog,
    ]);
}


    /**
     * Compare captured face image with precomputed face encoding
     */
    private function compareFacesWithEncoding($capturedImagePath, $storedEncoding)
    {
        $pythonScript = base_path('verify_face.py');

        $command = escapeshellcmd(
            "python " . escapeshellarg($pythonScript) . " "
            . escapeshellarg($capturedImagePath) . " "
            . escapeshellarg($storedEncoding)
        );

        $output = shell_exec($command);
        $result = json_decode($output, true);

        if (isset($result['error'])) {
            \Log::error("Face verification error: " . $result['error']);
            return false;
        }

        return !empty($result['match']) && $result['match'] === true;
    }
}
