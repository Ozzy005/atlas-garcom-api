<?php

namespace App\Models;

use App\Traits\Tenant;
use Illuminate\Database\Eloquent\Builder;
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
 * @property \App\Models\MeasurementUnit $measurementUnit
 * @property \App\Models\Tenant $tenant
 */

class Complement extends Model
{
    use HasFactory, Tenant;

    protected $fillable = [
        'name',
        'description',
        'measurement_unit_id',
        'quantity',
        'cost_price',
        'price',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'measurement_unit_id' => 'integer',
        'quantity' =>   'float',
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

    public function measurementUnit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function scopeTenantQuery(Builder $query): void
    {
        $query->where('complements.tenant_id', $this->getTenantId());
    }

    public function scopeMeasurementUnitQuery(Builder $query): void
    {
        $query->select(
            'complements.*',
            'measurement_units.name as measurement_unit'
        )
            ->join('measurement_units', 'measurement_units.id', '=', 'complements.measurement_unit_id');
    }
}
