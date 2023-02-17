<?php

namespace App\Models;

use App\Enums\TenantStatus;
use App\Traits\PersonAccessors;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model
{
    use HasFactory, PersonAccessors;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => TenantStatus::class
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status_name'
    ];

    /**
     * Get the status name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function statusName(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status->name()
        );
    }

    /**
     * Get the person that owns the Tenant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
