<?php

namespace App\Models;

use App\Enums\Recurrence;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $color
 * @property \App\Enums\Recurrence $recurrence
 * @property float $price
 * @property bool $has_discount
 * @property float $discount
 * @property float $discounted_price
 * @property float $total_price
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $dueDays
 * @property \Illuminate\Database\Eloquent\Collection $modules
 * @property \Illuminate\Support\Collection  $due_days_ids
 * @property \Illuminate\Support\Collection  $modules_ids
 */

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

    public function dueDaysIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('dueDays') ? $this->dueDays->pluck('id') : []
        );
    }
}
