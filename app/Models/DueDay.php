<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
