<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'subcategory_id', 'price', 'description',
        'short_description', 'tag_type', 'tag_label', 'stock_qty', 'track_stock',
        'is_active', 'is_featured', 'meta_title', 'meta_description', 'sort_order',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'track_stock' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'subcategory_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): BelongsTo|HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true)->limit(1);
    }

    public function getPrimaryImagePathAttribute(): ?string
    {
        return $this->images->where('is_primary', true)->first()?->path
            ?? $this->images->first()?->path;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'K' . number_format($this->price, 0);
    }

    public function isInStock(): bool
    {
        return !$this->track_stock || $this->stock_qty > 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
