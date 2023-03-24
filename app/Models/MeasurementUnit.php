<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'initials',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'initials' => 'string',
        'status' => Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
