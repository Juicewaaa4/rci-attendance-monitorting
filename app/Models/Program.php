<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';
    protected $primaryKey = 'program_id';
    public $incrementing = false; // Since program_id is a UUID
    protected $keyType = 'string';
    protected $fillable = ['program_id', 'program_name', 'program_description', 'program_code', 'department_id', ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->program_id)) {
                $model->program_id = Str::uuid();
            }
        });
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('programs_page_1');
        });

        static::deleted(function () {
            Cache::forget('programs_page_1');
        });
    }

    // Relationship with Assignments
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'program_id', 'program_id');
    }

    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class, 'program_id', 'program_id');
    }
}
