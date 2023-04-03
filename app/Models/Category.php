<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property string $image
 * @property string $name
 * @property string $description
 * @property \App\Enums\Status $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property string $image_url
 * @property \App\Models\Tenant $tenant
 */

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'image' => 'string',
        'name' => 'string',
        'description' => 'string',
        'status' => \App\Enums\Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'image_url'
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function imageUrl(): Attribute
    {
        return new Attribute(
            get: fn () => !empty($this->image) ? asset('storage/' . $this->image) : asset('images/no-image.png')
        );
    }
}
