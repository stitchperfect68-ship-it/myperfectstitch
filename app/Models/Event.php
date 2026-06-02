<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title', 'slug', 'tag', 'event_type', 'description',
        'gallery_layout', 'text_first', 'event_date', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'text_first' => 'boolean',
        'event_date' => 'date',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class)->orderBy('sort_order');
    }

    public function heroImage(): ?EventImage
    {
        return $this->images->where('role', 'hero')->first()
            ?? $this->images->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
