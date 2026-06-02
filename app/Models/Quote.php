<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'ref', 'service_type', 'quantity', 'budget', 'description',
        'deadline', 'name', 'phone', 'email', 'status',
        'quoted_amount', 'admin_notes', 'payment_link', 'replied_at',
    ];

    protected $casts = [
        'replied_at'    => 'datetime',
        'quoted_amount' => 'decimal:2',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function generateRef(): string
    {
        return 'QT-' . strtoupper(substr(uniqid(), -6)) . '-' . date('Y');
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'new'       => 'blue',
            'reviewed'  => 'yellow',
            'quoted'    => 'purple',
            'converted' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }
}
