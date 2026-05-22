<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';
    protected $primaryKey = 'section_id';
    public $incrementing = false; // Since section_id is a UUID
    protected $keyType = 'string';
    protected $fillable = ['section_id', 'section_name', 'section_description' ,'section_code','program_id', 'department_id', 'year_level_id', 'section_adviser'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->section_id)) {
                $model->section_id = Str::uuid();
            }
        });
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('sections'); 
        });

        static::deleted(function () {
            Cache::forget('sections'); 
        });
    }

    // Relationship with Assignments
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'year_level_id');
    }
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'school_year_id');
    }

    public function sectionAdviser()
    {
        return $this->belongsTo(Account::class, 'section_adviser', 'account_id');
    }
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'section_id', 'section_id');
    }

    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class, 'section_id', 'section_id');
    }
}
