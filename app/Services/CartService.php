<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Session\SessionManager;

class CartService
{
    private const KEY = 'cart';

    public function __construct(private SessionManager $session) {}

    public function all(): array
    {
        return $this->session->get(self::KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->all();
        $id   = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'slug'     => $product->slug,
                'price'    => $product->price,
                'image'    => $product->primary_image_path,
                'quantity' => $quantity,
            ];
        }

        $this->session->put(self::KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->all();
        unset($cart[$productId]);
        $this->session->put(self::KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->all();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }

        $this->session->put(self::KEY, $cart);
    }

    public function clear(): void
    {
        $this->session->forget(self::KEY);
    }

    public function total(): float
    {
        return collect($this->all())->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function count(): int
    {
        return collect($this->all())->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return empty($this->all());
    }
}
