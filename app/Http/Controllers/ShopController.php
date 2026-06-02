<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Services\CartService;

class ShopController extends Controller
{
    public function show(Product $product, CartService $cart)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['images', 'category', 'subcategory']);
        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->limit(4)
            ->get();

        $whatsappNumber = Setting::get('whatsapp_number', '260968531630');

        return view('shop.show', compact('product', 'related', 'whatsappNumber', 'cart'));
    }
}
