<?php

namespace App\Models;

use App\Enums\Recurrence;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'recurrence',
        'price',
        'has_discount',
        'discount',
        'discounted_price',
        'total_price',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'color' => 'string',
        'recurrence' => Recurrence::class,
        'price' => 'float',
        'has_discount' => 'boolean',
        'discount' => 'float',
        'discounted_price' => 'float',
        'total_price' => 'float',
        'status' => Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'due_days_ids',
        'modules_ids'
    ];

    public function dueDays(): BelongsToMany
    {
        return $this->belongsToMany(DueDay::class, 'due_day_signature');
    }

    public function dueDaysIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('dueDays') ? $this->dueDays->pluck('id') : []
        );
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function modulesIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('modules') ? $this->modules->pluck('id') : []
        );
    }
}
