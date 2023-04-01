<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $initials
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */

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
        'status' => \App\Enums\Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
