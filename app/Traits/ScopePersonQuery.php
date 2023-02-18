<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait ScopePersonQuery
{
    /**
     * Scope a query to include people information.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePersonQuery($query)
    {
        return $query->select(
            "{$this->getTable()}.*",
            DB::raw("concat(people.full_name, ' - ', people.nif) as info"),
            'people.nif',
            'people.full_name',
            'people.name',
            'people.state_registration',
            'people.city_registration',
            'people.birthdate',
            'people.email',
            'people.phone',
            'people.city_id',
            DB::raw("concat(cities.title, ' - ', states.letter) as city"),
            'people.zip_code',
            'people.address',
            'people.district',
            'people.number',
            'people.complement',
        )
            ->leftJoin('people', 'people.id', '=', "{$this->getTable()}.person_id")
            ->leftJoin('cities', 'cities.id', '=', 'people.city_id')
            ->leftJoin('states', 'states.id', '=', 'cities.state_id');
    }
}
