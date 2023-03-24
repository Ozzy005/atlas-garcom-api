<?php

namespace App\Models;

use App\Enums\TenantStatus;
use App\Traits\ScopePersonQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'status' => TenantStatus::class,
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
}
