<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::parents()->with('children')->get();

        $query = Product::active()->with(['images', 'category', 'subcategory']);

        if ($search = trim((string) $request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($subcategory = $request->input('subcategory')) {
            $query->whereHas('subcategory', fn ($q) => $q->where('slug', $subcategory));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        if ($request->boolean('in_stock')) {
            $query->where(function ($q) {
                $q->where('track_stock', false)->orWhere('stock_qty', '>', 0);
            });
        }

        match ($request->input('sort')) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name'       => $query->orderBy('name'),
            'newest'     => $query->latest(),
            default      => $query->orderBy('sort_order')->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        $whatsappNumber = Setting::get('whatsapp_number', '260968531630');

        return view('shop.index', compact('products', 'categories', 'whatsappNumber'));
    }

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
