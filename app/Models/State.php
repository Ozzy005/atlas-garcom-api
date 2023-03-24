<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'letter' => 'string',
        'iso' => 'integer',
        'slug' => 'string',
        'population' => 'integer'
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
