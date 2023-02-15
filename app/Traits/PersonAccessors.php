<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait PersonAccessors
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $appends = [
            'info',
            'nif',
            'full_name',
            'name',
            'state_registration',
            'city_registration',
            'birthdate',
            'email',
            'phone',
            'city_id',
            'city',
            'zip_code',
            'address',
            'district',
            'number',
            'complement'
        ];


        $this->appends = array_merge($appends, $this->appends);
    }

    public function info(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!empty($this->person->full_name) && !empty(nifMask($this->person->nif))) {
                    return ($this->person->full_name . ' - ' . nifMask($this->person->nif));
                }
                if (!empty($this->person->name) && !empty(nifMask($this->person->nif))) {
                    return ($this->person->name . ' - ' . nifMask($this->person->nif));
                }
                if (!empty($this->person->full_name)) {
                    return $this->person->full_name;
                }
                if (!empty($this->person->name)) {
                    return $this->person->name;
                }
                return null;
            }
        );
    }


    public function nif(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->person->nif) ? nifMask($this->person->nif) : null
        );
    }

    public function fullName(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->full_name ?? null
        );
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->name ?? null
        );
    }

    public function stateRegistration(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->state_registration ?? null
        );
    }

    public function cityRegistration(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->city_registration ?? null
        );
    }

    public function birthdate(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->birthdate ?? null
        );
    }

    public function email(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->email ?? null
        );
    }

    public function phone(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->person->phone) ? phoneMask($this->person->phone) : null
        );
    }

    public function cityId(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->city_id ?? null
        );
    }

    public function city(): Attribute
    {
        return new Attribute(
            get: function () {
                if (!empty($this->person->city_id)) {
                    return $this->person->city->info;
                }
                return null;
            }
        );
    }

    public function zipCode(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->person->zip_code) ? zipCodeMask($this->person->zip_code) : null
        );
    }

    public function address(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->address ?? null
        );
    }

    public function district(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->district ?? null
        );
    }

    public function number(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->number ?? null
        );
    }

    public function complement(): Attribute
    {
        return new Attribute(
            get: fn () => $this->person->complement ?? null
        );
    }
}
