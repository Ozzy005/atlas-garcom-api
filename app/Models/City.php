<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    use HasFactory;

    /**
     * Get the state that owns the City
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Scope a query to include state information.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStateQuery($query)
    {
        return $query->select(
            'cities.*',
            DB::raw("concat(cities.title, ' - ', states.letter) as info"),
            'states.title as state',
            'states.letter'
        )
            ->join('states', 'states.id', '=', 'cities.state_id');
    }
}
