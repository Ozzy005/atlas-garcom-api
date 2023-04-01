<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $day
 * @property string $description
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */

class DueDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'description',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'day' => 'integer',
        'description' => 'string',
        'status' => \App\Enums\Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
