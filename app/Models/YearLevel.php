<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YearLevel extends Model
{
    use HasFactory;

    protected $table = 'year_levels';
    protected $primaryKey = 'year_level_id';
    public $incrementing = false; // Since year_level_id is a UUID
    protected $keyType = 'string';

    // Added 'year_level_code' to the fillable fields
    protected $fillable = [
        'year_level_id',
        'year_level_name',
        'year_level_description',
        'department_id',
        'year_level_code'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->year_level_id)) {
                $model->year_level_id = Str::uuid();
            }
        });
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('yearlevels');
        });

        static::deleted(function () {
            Cache::forget('yearlevels');
        });
    }

    // Relationship with Assignments
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'year_level_id', 'year_level_id');
    }

    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class, 'year_level_id', 'year_level_id');
    }
}
