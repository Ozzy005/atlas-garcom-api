<?php

namespace App\Models;

use App\Traits\Tenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $product_id
 * @property string $name
 * @property integer $measurement_unit_id
 * @property float $quantity
 * @property float $cost_price
 * @property float $price
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \App\Models\Product $product
 * @property \App\Models\MeasurementUnit $measurementUnit
 */

class ProductPrice extends Model
{
    use HasFactory, Tenant;

    protected $fillable = [
        'product_id',
        'name',
        'measurement_unit_id',
        'quantity',
        'cost_price',
        'price',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'name' => 'string',
        'measurement_unit_id' => 'integer',
        'quantity' => 'float',
        'cost_price' => 'float',
        'price' => 'float',
        'status' => \App\Enums\Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class);
    }
}
