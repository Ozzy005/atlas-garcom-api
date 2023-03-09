<?php

namespace App\Models;

use App\Enums\Recurrence;
use App\Enums\Status;
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
        'recurrence',
        'price',
        'hasDiscount',
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
        'hasDiscount' => 'boolean',
        'recurrence' => Recurrence::class,
        'status' => Status::class
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

    /**
     * The modules that belong to the Signature
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
