<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $primaryKey = 'department_id';
    public $incrementing = false; // Since department_id is a UUID
    protected $keyType = 'string';
    protected $fillable = ['department_id', 'department_code' ,'department_name', 'department_description'];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->department_id)) {
                $model->department_id = Str::uuid();
            }
        });
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('departments'); 
        });

        static::deleted(function () {
            Cache::forget('departments'); 
        });
    }

    // Relationship with Assignments   

    public function programs()
    {
        return $this->hasMany(Assignment::class, 'program_id', 'program_id');
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'department_id', 'department_id');
    }

    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class, 'department_id', 'department_id');
    }
}
