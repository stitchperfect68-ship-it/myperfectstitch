<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function show(string $ref)
    {
        $order = Order::with(['items', 'payment'])->where('ref', $ref)->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
