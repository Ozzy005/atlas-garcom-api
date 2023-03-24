<?php

namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'guard_name' => 'string',
        'type' => RoleType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'permissions_ids'
    ];

    public function permissionsIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('permissions') ? $this->permissions->pluck('id') : []
        );
    }
}
