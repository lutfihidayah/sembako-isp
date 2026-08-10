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
        'category',
        'is_active',
    ];

    protected $casts = [
        'items'     => 'array',
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
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/package-placeholder.png');
    }
}
