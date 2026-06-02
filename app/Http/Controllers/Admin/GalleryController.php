<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Services\MediaService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index(Request $request)
    {
        $query = GalleryItem::orderBy('sort_order');
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->paginate(24)->withQueryString();
        $categories = GalleryItem::distinct()->pluck('category');

        return view('admin.gallery.index', compact('items', 'categories'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'image'    => 'required|image|max:10240',
            'alt'      => 'nullable|string|max:255',
            'caption'  => 'nullable|string|max:255',
        ]);

        $path = $this->media->upload($request->file('image'), 'gallery');

        GalleryItem::create([
            'category'   => $request->category,
            'path'       => $path,
            'alt'        => $request->alt,
            'caption'    => $request->caption,
            'sort_order' => GalleryItem::where('category', $request->category)->count(),
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item added.');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'images.*' => 'required|image|max:10240',
        ]);

        $count = 0;
        foreach ($request->file('images', []) as $file) {
            $path = $this->media->upload($file, 'gallery');
            GalleryItem::create([
                'category'   => $request->category,
                'path'       => $path,
                'alt'        => $request->category,
                'sort_order' => GalleryItem::where('category', $request->category)->count(),
            ]);
            $count++;
        }

        return redirect()->route('admin.gallery.index')->with('success', "{$count} images uploaded.");
    }

    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $request->validate([
            'category'  => 'required|string|max:100',
            'alt'       => 'nullable|string|max:255',
            'caption'   => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order'=> 'nullable|integer',
        ]);

        $gallery->update($request->only('category', 'alt', 'caption', 'is_active', 'sort_order'));

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated.');
    }

    public function destroy(GalleryItem $gallery)
    {
        $this->media->delete($gallery->path);
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted.');
    }
}
