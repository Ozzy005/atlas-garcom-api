<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'info',
        'state_name'
    ];

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
     * Get the state and city as a concatenated attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function info(): Attribute
    {
        return new Attribute(
            get: fn () => $this->title . ' - ' . $this->state->letter
        );
    }

    /**
     * Get the state name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function stateName(): Attribute
    {
        return new Attribute(
            get: fn () => $this->state->title
        );
    }
}
