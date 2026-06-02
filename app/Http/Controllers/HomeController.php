<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\TeamMember;
use App\Services\CartService;

class HomeController extends Controller
{
    public function index(CartService $cart)
    {
        $sliders = Slider::active()->get();
        $categories = ProductCategory::parents()->with('children')->get();
        $products = Product::active()->with(['images', 'category', 'subcategory'])->orderBy('sort_order')->get();
        $clients = Client::active()->get();
        $team = TeamMember::active()->get();

        $whatsappNumber = Setting::get('whatsapp_number', '260968531630');

        return view('home', compact('sliders', 'categories', 'products', 'clients', 'team', 'whatsappNumber', 'cart'));
    }
}
