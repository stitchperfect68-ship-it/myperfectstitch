<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::active()->get();
        $categories = $items->pluck('category')->unique()->values();

        return view('gallery', compact('items', 'categories'));
    }
}
