<?php

namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'guard_name' => 'string',
        'tenant_id' => 'integer',
        'type' => RoleType::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'permissions_ids'
    ];

    public function scopeTenantQuery(Builder $query): void
    {
        $user = User::query()
            ->find(auth()->id());

        $query->where(function ($query) use ($user) {
            $query->when($user->is_admin->yes() || $user->is_provider_employee, function ($query) {
                $query->whereNull('tenant_id');
            })
                ->when($user->is_tenant->yes(), function ($query) use ($user) {
                    $user->load('tenant');
                    $query->where('tenant_id', $user->tenant->id);
                })
                ->when($user->is_tenant_employee, function ($query) use ($user) {
                    $user->load('employer');
                    $query->where('tenant_id', $user->employer->id);
                });
        });
    }

    public function permissionsIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('permissions') ? $this->permissions->pluck('id') : []
        );
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
