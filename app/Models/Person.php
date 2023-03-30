<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'nif',
        'full_name',
        'name',
        'state_registration',
        'city_registration',
        'birthdate',
        'email',
        'phone',
        'city_id',
        'zip_code',
        'address',
        'district',
        'number',
        'complement'
    ];

    protected $casts = [
        'id' => 'integer',
        'nif' => 'string',
        'full_name' => 'string',
        'name' => 'string',
        'state_registration' => 'string',
        'city_registration' => 'string',
        'birthdate' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'city_id' => 'integer',
        'zip_code' => 'string',
        'address' => 'string',
        'district' => 'string',
        'number' => 'string',
        'complement' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
