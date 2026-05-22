<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'accounts'; // Explicitly set the table name

    protected $primaryKey = 'account_id'; // Set the primary key

    public $incrementing = false; // Since `account_id` is a UUID, it should not auto-increment

    protected $keyType = 'string'; // UUIDs are stored as strings

    protected $fillable = [
        'account_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'otp',
        'otp_expiry',
        'role',
    ];

    protected $hidden = [
        'password',
        'otp',
    ];

    protected $casts = [
        'account_id' => 'string',
        'role' => 'string',
    ];

    /**
     * Automatically generate a UUID for account_id on creation.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->account_id)) {
                $model->account_id = Str::uuid();
            }
        });
    }

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('accounts'); 
        });

        static::deleted(function () {
            Cache::forget('accounts'); 
        });
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'instructor_id', 'account_id');
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'department_head', 'account_id');
    }
}
