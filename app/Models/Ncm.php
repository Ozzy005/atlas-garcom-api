<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property \Illuminate\Support\Carbon $date_start
 * @property \Illuminate\Support\Carbon $date_end
 * @property string $ato_type
 * @property string $ato_number
 * @property string $ato_year
 */

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
