<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'transaction_id', 'lenco_reference', 'payment_reference', 'gateway', 'amount',
        'fee', 'currency', 'status', 'payment_method', 'operator', 'card_last4',
        'gateway_response', 'payment_url', 'paid_at',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'fee'              => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at'          => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
