<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        return view('cart', ['cart' => $this->cart]);
    }

    public function add(Request $request, Product $product)
    {
        $quantity = max(1, (int) $request->input('quantity', 1));

        if (!$product->isInStock()) {
            return back()->with('error', 'This product is out of stock.');
        }

        $this->cart->add($product, $quantity);

        if ($request->wantsJson()) {
            return response()->json([
                'count'   => $this->cart->count(),
                'total'   => $this->cart->total(),
                'message' => 'Added to cart',
            ]);
        }

        return back()->with('success', "{$product->name} added to cart.");
    }

    public function remove(Request $request, int $productId)
    {
        $this->cart->remove($productId);

        if ($request->wantsJson()) {
            return response()->json(['count' => $this->cart->count(), 'total' => $this->cart->total()]);
        }

        return back()->with('success', 'Item removed from cart.');
    }

    public function update(Request $request)
    {
        $items = $request->input('items', []);
        foreach ($items as $id => $qty) {
            $this->cart->update((int) $id, (int) $qty);
        }

        return back()->with('success', 'Cart updated.');
    }
}
