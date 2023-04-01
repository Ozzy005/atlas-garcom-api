<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $tenant_id
 * @property \App\Enums\RoleType $type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \App\Models\Tenant $tenant
 * @property \Illuminate\Database\Eloquent\Collection $permissions
 * @property \Illuminate\Support\Collection  $permissions_ids
 */

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'guard_name' => 'string',
        'tenant_id' => 'integer',
        'type' => \App\Enums\RoleType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'permissions_ids'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function permissionsIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('permissions') ? $this->permissions->pluck('id') : []
        );
    }

    public function scopeTenantQuery(Builder $query): void
    {
        /** @var \App\Models\User $user */
        $user = User::query()
            ->find(auth()->id());

        $query->where(function (Builder $query) use ($user) {
            $query->when($user->is_admin->yes() || $user->is_provider_employee, function (Builder $query) {
                $query->whereNull('tenant_id');
            })
                ->when($user->is_tenant->yes(), function (Builder $query) use ($user) {
                    $user->load('tenant');
                    $query->where('tenant_id', $user->tenant->id);
                })
                ->when($user->is_tenant_employee, function (Builder $query) use ($user) {
                    $user->load('employer');
                    $query->where('tenant_id', $user->employer->id);
                });
        });
    }
}
