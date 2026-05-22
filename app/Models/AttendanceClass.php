<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceClass extends Model
{
    use HasFactory;

    protected $table = 'attendance_classes';

    protected $fillable = [
        'student_name',
        'program',
        'section',
        'year_level',
        'date',
        'time',
        'attendance_status',  // ✅ FIXED: must match DB column
        'instructor',
        'action',
        'image',
        // 'remarks',
    ];
}
