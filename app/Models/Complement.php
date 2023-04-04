<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property float $cost_price
 * @property float $price
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property string $image_url
 * @property \App\Models\Tenant $tenant
 */

class Complement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'cost_price',
        'price',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'cost_price' => 'float',
        'price' => 'float',
        'status' => \App\Enums\Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
