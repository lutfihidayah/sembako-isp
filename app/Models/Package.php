<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'items',
        'price',
        'stock',
        'image',
        'images',
        'category',
        'is_active',
    ];

    protected $casts = [
        'items'     => 'array',
        'images'    => 'array',
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function getImageUrlAttribute(): string
    {
        $primary = $this->primary_image;
        if ($primary) {
            return asset('storage/' . $primary);
        }
        return asset('images/package-placeholder.png');
    }

    public function getPrimaryImageAttribute(): ?string
    {
        if (!empty($this->images) && is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        return $this->image ?: null;
    }

    public function getAllImagesAttribute(): array
    {
        if (!empty($this->images) && is_array($this->images) && count($this->images) > 0) {
            return $this->images;
        }
        return $this->image ? [$this->image] : [];
    }
}
