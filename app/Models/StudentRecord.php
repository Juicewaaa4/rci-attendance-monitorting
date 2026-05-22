<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentRecord extends Model
{
    use HasFactory;

    protected $table = 'student_records'; // Set the table name

    protected $primaryKey = 'record_id'; // Define primary key
    public $incrementing = false; // UUIDs are not auto-incrementing
    protected $keyType = 'string'; // Define UUID as a string

    protected $fillable = [
        'record_id',
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'section_id',
        'year_level_id',
        'school_year_id', 
        'program_id',
        'department_id',
        'rfid',
        'face_image'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->record_id)) {
                $model->record_id = Str::uuid();
            }
        });
    }


    /**
     * Relationships
     */

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

    public function yearLevel(): BelongsTo
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'year_level_id');
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'school_year_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
