<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'ref', 'customer_id', 'customer_name', 'customer_phone', 'customer_email',
        'quote_id', 'status', 'subtotal', 'total', 'notes',
        'shipping_street', 'shipping_city', 'shipping_country',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public static $statuses = [
        'pending_payment' => 'Pending Payment',
        'paid'            => 'Paid',
        'processing'      => 'Processing',
        'ready'           => 'Ready for Pickup/Dispatch',
        'dispatched'      => 'Dispatched',
        'delivered'       => 'Delivered',
        'cancelled'       => 'Cancelled',
        'refunded'        => 'Refunded',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public static function generateRef(): string
    {
        return 'MPS-' . strtoupper(substr(uniqid(), -6)) . '-' . date('Y');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'yellow',
            'paid'            => 'blue',
            'processing'      => 'indigo',
            'ready'           => 'purple',
            'dispatched'      => 'orange',
            'delivered'       => 'green',
            'cancelled'       => 'red',
            'refunded'        => 'gray',
            default           => 'gray',
        };
    }
}
