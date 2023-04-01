<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property string $title
 * @property integer $iso
 * @property integer $iso_ddd
 * @property integer $status
 * @property string $slug
 * @property integer $population
 * @property float $lat
 * @property float $long
 * @property float $income_per_capita
 * @property integer $state_id
 *
 * @property \App\Models\State $state
 */

class City extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'iso' => 'integer',
        'iso_ddd' => 'integer',
        'status' => 'integer',
        'slug' => 'string',
        'population' => 'integer',
        'lat' => 'float',
        'long' => 'float',
        'income_per_capita' => 'float',
        'state_id' => 'integer'
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function scopeStateQuery(Builder $query): void
    {
        $query->select(
            'cities.*',
            DB::raw("concat(cities.title, ' - ', states.letter) as info"),
            'states.title as state',
            'states.letter'
        )
            ->join('states', 'states.id', '=', 'cities.state_id');
    }
}
