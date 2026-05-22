<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Auth\Authenticatable; 
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;


class Machine extends Model implements AuthenticatableContract
{
    use HasFactory;
    use Authenticatable;

    protected $table = 'machines';
    protected $primaryKey = 'machine_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'machine_id', 'machine_name', 'password'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->machine_id)) {
                $model->machine_id = Str::uuid();
            }
        });
    }

}
