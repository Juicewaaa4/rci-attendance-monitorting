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
    $rfidImagesFolder = public_path('rfid_images');
    if (!is_dir($rfidImagesFolder)) {
        mkdir($rfidImagesFolder, 0755, true);
    }
    $capturedImagePath = $rfidImagesFolder . '/' . $capturedImageName;
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
    $capturedEncoding = $request->input('face_encoding');
    if (!$capturedEncoding) {
        if (file_exists($capturedImagePath)) unlink($capturedImagePath);
        return response()->json([
            'success' => false,
            'message' => 'No face detected in the image. Please look at the camera.',
        ]);
    }

    $match = $this->compareFacesWithEncoding($capturedEncoding, $storedEncoding);

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
     * Compare captured face encoding with precomputed face encoding
     * using Euclidean distance (standard for face-api.js descriptors)
     */
    private function compareFacesWithEncoding($capturedEncodingStr, $storedEncodingStr)
    {
        $capturedEncoding = is_array($capturedEncodingStr) ? $capturedEncodingStr : json_decode($capturedEncodingStr, true);
        $storedEncoding = is_array($storedEncodingStr) ? $storedEncodingStr : json_decode($storedEncodingStr, true);

        if (!is_array($capturedEncoding) || !is_array($storedEncoding) || count($capturedEncoding) !== 128 || count($storedEncoding) !== 128) {
            \Log::error("Invalid face encodings during verification");
            return false;
        }

        $sum = 0;
        for ($i = 0; $i < 128; $i++) {
            $diff = $capturedEncoding[$i] - $storedEncoding[$i];
            $sum += $diff * $diff;
        }
        $distance = sqrt($sum);

        \Log::info("Face distance: " . $distance);

        // face-api.js threshold is usually around 0.5 to 0.6
        return $distance < 0.55;
    }
}
