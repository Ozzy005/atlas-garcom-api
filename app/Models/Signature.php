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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'has_discount' => 'boolean',
        'recurrence' => Recurrence::class,
        'status' => Status::class
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'due_days_ids',
        'modules_ids'
    ];

    /**
     * The dueDays that belong to the Signature
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dueDays(): BelongsToMany
    {
        return $this->belongsToMany(DueDay::class, 'due_day_signature');
    }

    public function dueDaysIds(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->dueDays) ? $this->dueDays->pluck('id') : []
        );
    }

    /**
     * The modules that belong to the Signature
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function modulesIds(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->modules) ? $this->modules->pluck('id') : []
        );
    }
}
