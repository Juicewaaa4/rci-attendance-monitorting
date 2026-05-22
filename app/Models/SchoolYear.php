<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolYear extends Model
{

    use HasFactory;

    protected $table = 'school_years';
    protected $primaryKey = 'school_year_id';
    public $incrementing = false; // Since year_level_id is a UUID
    protected $keyType = 'string';
    protected $fillable = ['school_year_id', 'school_year_name', 'school_year_description', 'school_year_code'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->school_year_id)) {
                $model->school_year_id = Str::uuid();
            }
        });
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('schoolyears');
        });

        static::deleted(function () {
            Cache::forget('schoolyears');
        });
    }

    // Relationship with Assignments
    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class, 'school_year_id', 'school_year_id');
    }
    public function sections()
    {
        return $this->hasMany(Section::class, 'school_year_id', 'school_year_id');
    }
}