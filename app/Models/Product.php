<?php

namespace App\Models;

use App\Traits\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $image
 * @property string $name
 * @property string $description
 * @property integer $category_id
 * @property integer $tenant_id
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property \App\Models\Tenant $tenant
 * @property \App\Models\Category $category
 * @property \App\Models\ProductPrice $productPrices
 * @property \App\Models\Complements $complements
 * @property \Illuminate\Support\Collection  $permissions_ids
 * @property string $image_url
 */

class Product extends Model
{
    use HasFactory, Tenant;

    protected $fillable = [
        'image',
        'name',
        'description',
        'category_id',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'image' => 'string',
        'name' => 'string',
        'description' => 'string',
        'category_id' => 'integer',
        'tenant_id' => 'integer',
        'status' => \App\Enums\Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'image_url',
        'complements_ids'
    ];

    public function complementsIds(): Attribute
    {
        return new Attribute(
            get: fn () => $this->relationLoaded('complements') ? $this->complements->pluck('id') : []
        );
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function complements(): BelongsToMany
    {
        return $this->belongsToMany(Complement::class);
    }

    public function imageUrl(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->image) ? asset('storage/' . $this->image) : asset('storage/images/no-image.png')
        );
    }

    public function scopeTenantQuery(Builder $query): void
    {
        $query->where('tenant_id', $this->getTenantId());
    }
}
