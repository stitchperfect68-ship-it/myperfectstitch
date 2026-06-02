<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderService
{
    public function __construct(private CartService $cart) {}

    public function createFromCart(array $customerData): Order
    {
        $items = $this->cart->all();

        if (empty($items)) {
            throw new \RuntimeException('Cart is empty.');
        }

        $subtotal = $this->cart->total();

        $order = Order::create([
            'ref'            => Order::generateRef(),
            'customer_id'    => $customerData['customer_id'] ?? null,
            'customer_name'  => $customerData['name'],
            'customer_phone' => $customerData['phone'],
            'customer_email' => $customerData['email'] ?? null,
            'status'         => 'pending_payment',
            'subtotal'       => $subtotal,
            'total'          => $subtotal,
            'notes'          => $customerData['notes'] ?? null,
            'shipping_street'  => $customerData['street'] ?? null,
            'shipping_city'    => $customerData['city'] ?? null,
            'shipping_country' => $customerData['country'] ?? 'Zambia',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item['id'],
                'product_name'  => $item['name'],
                'product_image' => $item['image'],
                'price'         => $item['price'],
                'quantity'      => $item['quantity'],
                'subtotal'      => $item['price'] * $item['quantity'],
            ]);

            // Decrement stock if tracked
            Product::where('id', $item['id'])
                ->where('track_stock', true)
                ->decrement('stock_qty', $item['quantity']);
        }

        $this->cart->clear();

        return $order;
    }
}
