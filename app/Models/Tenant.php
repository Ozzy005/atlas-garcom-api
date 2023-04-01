<?php

namespace App\Models;

use App\Traits\ScopePersonQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property integer $id
 * @property string $person_id
 * @property string $signature_id
 * @property integer $due_day_id
 * @property \App\Enums\TenantStatus $status
 * @property integer $population
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \App\Models\Person $person
 * @property \App\Models\Signature $signature
 * @property \App\Models\DueDay $dueDay
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $employees
 */

class Tenant extends Model
{
    use HasFactory, ScopePersonQuery;

    protected $fillable = [
        'person_id',
        'signature_id',
        'due_day_id',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'person_id' => 'integer',
        'signature_id' => 'integer',
        'due_day_id' => 'integer',
        'status' => \App\Enums\TenantStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function signature(): BelongsTo
    {
        return $this->belongsTo(Signature::class);
    }

    public function dueDay(): BelongsTo
    {
        return $this->belongsTo(DueDay::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'employer_id');
    }
}
