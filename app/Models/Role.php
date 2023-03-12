<?php

namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => RoleType::class
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'permissions_ids'
    ];

    public function permissionsIds(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->permissions) ? $this->permissions->pluck('id') : []
        );
    }
}
