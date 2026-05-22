<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';
    protected $primaryKey = 'assignment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'assignment_id', 'instructor_id', 'section_id', 'year_level_id', 'program_id', 'department_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->assignment_id)) {
                $model->assignment_id = Str::uuid();
            }
        });
    }


    // Relationship with Account (Teachers)
    public function teacher()
    {
        return $this->belongsTo(Account::class, 'instructor_id', 'account_id');
    }

    // Relationship with Section
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

    // Relationship with Year Level
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'year_level_id');
    }

    // Relationship with Program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    // Relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
}
