<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\StudentRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RFIDLog extends Model
{
    use HasFactory;

    protected $table = 'rfid_logs';
    protected $primaryKey = 'rfid_log_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rfid_log_id',
        'record_id',
        'action',
        'scanned_at',
        'image'
    ];


    protected $casts = [
        'scanned_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // Relationship (update foreign key name)
    public function student()
    {
        return $this->belongsTo(StudentRecord::class, 'record_id', 'record_id');
    }



    // Interval since last login
    public function getDurationSinceLastLoginAttribute()
    {
        if ($this->action !== 'Log-out' || !$this->student) {
            return '00:00:00';
        }
    
        $previousLogin = self::where('record_id', $this->record_id)
            ->where('action', 'Log-in')
            ->where('created_at', '<', $this->created_at)
            ->latest()
            ->first();
    
        return $previousLogin
            ? $this->created_at->diffForHumans($previousLogin->created_at)
            : '00:00:00';
    }
    
}
