<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\IsAdmin;
use App\Enums\IsEmployee;
use App\Enums\IsTenant;
use App\Enums\Status;
use App\Traits\ScopePersonQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Carbon;

/**
 * @property integer $id
 * @property integer $person_id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property integer $employer_id
 * @property integer $tenant_id
 * @property IsTenant $is_tenant
 * @property isEmployee $is_employee
 * @property IsAdmin $is_admin
 * @property Status $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
        'is_tenant' => IsTenant::class,
        'is_employee' => IsEmployee::class,
        'is_admin' => IsAdmin::class,
        'status' => Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'roles_ids',
        'is_tenant_employee',
        'is_provider_employee'
    ];

    public function scopeTenantQuery(Builder $query): void
    {
        $user = User::query()
            ->find(auth()->id());

        $query->where(function ($query) use ($user) {
            $query->when($user->is_admin->yes(), function ($query) {
                $query->whereNull('employer_id');
            })
                ->when($user->is_provider_employee, function ($query) use ($user) {
                    $query->whereNot('users.id', $user->id)
                        ->where('is_admin', IsAdmin::NOT)
                        ->whereNull('employer_id');
                })
                ->when($user->is_tenant->yes(), function ($query) use ($user) {
                    $user->load('tenant');
                    $query->whereNot('users.id', $user->id)
                        ->where('is_admin', IsAdmin::NOT)
                        ->where(function ($query) use ($user) {
                            $query->where('tenant_id', $user->tenant->id)
                                ->orWhere('employer_id', $user->tenant->id);
                        });
                })
                ->when($user->is_tenant_employee, function ($query) use ($user) {
                    $user->load('employer');
                    $query->whereNot('users.id', $user->id)
                        ->where('is_admin', IsAdmin::NOT)
                        ->whereNull('tenant_id')
                        ->where('employer_id', $user->employer->id);
                });
        });
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
}
