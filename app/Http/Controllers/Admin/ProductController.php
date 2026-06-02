<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory', 'images'])->orderBy('sort_order');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = ProductCategory::parents()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::parents()->with('children')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:product_categories,id',
            'subcategory_id'    => 'nullable|exists:product_categories,id',
            'price'             => 'required|numeric|min:0',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:300',
            'tag_type'          => 'nullable|string|max:50',
            'tag_label'         => 'nullable|string|max:50',
            'stock_qty'         => 'nullable|integer|min:0',
            'track_stock'       => 'boolean',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'sort_order'        => 'nullable|integer',
            'images.*'          => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $this->media->upload($file, 'products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => $path,
                    'alt'        => $product->name,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = ProductCategory::parents()->with('children')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:product_categories,id',
            'subcategory_id'    => 'nullable|exists:product_categories,id',
            'price'             => 'required|numeric|min:0',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:300',
            'tag_type'          => 'nullable|string|max:50',
            'tag_label'         => 'nullable|string|max:50',
            'stock_qty'         => 'nullable|integer|min:0',
            'track_stock'       => 'boolean',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'sort_order'        => 'nullable|integer',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            $this->media->delete($img->path);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function uploadImage(Request $request, Product $product)
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $path = $this->media->upload($request->file('image'), 'products');
        $image = ProductImage::create([
            'product_id' => $product->id,
            'path'       => $path,
            'alt'        => $product->name,
            'is_primary' => $product->images()->count() === 0,
            'sort_order' => $product->images()->count(),
        ]);

        return response()->json(['id' => $image->id, 'url' => $this->media->url($path)]);
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        $this->media->delete($image->path);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function reorderImages(Request $request, Product $product)
    {
        foreach ($request->input('order', []) as $sort => $id) {
            ProductImage::where('id', $id)->where('product_id', $product->id)->update(['sort_order' => $sort]);
        }

        return response()->json(['success' => true]);
    }
}
