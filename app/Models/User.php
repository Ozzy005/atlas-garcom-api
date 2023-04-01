<?php

namespace App\Models;

use App\Enums\IsAdmin;
use App\Traits\ScopePersonQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property integer $id
 * @property integer $person_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property integer $employer_id
 * @property integer $tenant_id
 * @property \App\Enums\IsTenant $is_tenant
 * @property \App\Enums\isEmployee $is_employee
 * @property \App\Enums\IsAdmin $is_admin
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \App\Models\Person $person
 * @property \App\Models\Tenant $employer
 * @property \App\Models\Tenant $tenant
 * @property \Illuminate\Database\Eloquent\Collection $roles
 * @property \Illuminate\Support\Collection $roles_ids
 * @property bool $is_tenant_employee
 * @property bool $is_provider_employee
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, ScopePersonQuery;

    protected $fillable = [
        'person_id',
        'name',
        'email',
        'password',
        'employer_id',
        'tenant_id',
        'is_tenant',
        'is_employee',
        'is_admin',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer',
        'person_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'remember_token' => 'string',
        'employer_id' => 'integer',
        'tenant_id' => 'integer',
        'is_tenant' => \App\Enums\IsTenant::class,
        'is_employee' => \App\Enums\IsEmployee::class,
        'is_admin' => \App\Enums\IsAdmin::class,
        'status' => \App\Enums\Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'roles_ids',
        'is_tenant_employee',
        'is_provider_employee'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'employer_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function rolesIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('roles') ? $this->roles->pluck('id') : []
        );
    }

    public function isTenantEmployee(): Attribute
    {
        return new Attribute(
            get: fn () => $this->employer_id && $this->is_employee->yes()
        );
    }

    public function isProviderEmployee(): Attribute
    {
        return new Attribute(
            get: fn () => !$this->employer_id && $this->is_employee->yes()
        );
    }

    public function scopeTenantQuery(Builder $query): void
    {
        /** @var \App\Models\User $user */
        $user = User::query()
            ->find(auth()->id());

        $query->where(function (Builder $query) use ($user) {
            $query->when($user->is_admin->yes(), function (Builder $query) {
                $query->whereNull('employer_id');
            })
                ->when($user->is_provider_employee, function (Builder $query) use ($user) {
                    $query->whereNot('users.id', $user->id)
                        ->where('is_admin', IsAdmin::NOT)
                        ->whereNull('employer_id');
                })
                ->when($user->is_tenant->yes(), function (Builder $query) use ($user) {
                    $user->load('tenant');
                    $query->whereNot('users.id', $user->id)
                        ->where('is_admin', IsAdmin::NOT)
                        ->where(function (Builder $query) use ($user) {
                            $query->where('tenant_id', $user->tenant->id)
                                ->orWhere('employer_id', $user->tenant->id);
                        });
                })
                ->when($user->is_tenant_employee, function (Builder $query) use ($user) {
                    $user->load('employer');
                    $query->whereNot('users.id', $user->id)
                        ->where('is_admin', IsAdmin::NOT)
                        ->whereNull('tenant_id')
                        ->where('employer_id', $user->employer->id);
                });
        });
    }
}
