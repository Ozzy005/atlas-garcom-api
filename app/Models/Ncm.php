<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ncm extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'date_start',
        'date_end',
        'ato_type',
        'ato_number',
        'ato_year'
    ];

    protected $casts = [
        'id' => 'integer',
        'code' => 'string',
        'description' => 'string',
        'date_start' => 'date',
        'date_end' => 'date',
        'ato_type' => 'string',
        'ato_number' => 'string',
        'ato_year' => 'string'
    ];
}
