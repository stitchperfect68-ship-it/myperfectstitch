<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioImage;
use App\Models\PortfolioProject;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index()
    {
        $projects = PortfolioProject::with('images')->orderBy('sort_order')->paginate(15);
        return view('admin.portfolio.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.portfolio.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'client_name'  => 'required|string|max:255',
            'client_badge' => 'nullable|string|max:255',
            'category'     => 'required|string|max:100',
            'description'  => 'nullable|string',
            'cta_text'     => 'nullable|string|max:100',
            'layout_type'  => 'nullable|string|max:50',
            'gallery_type' => 'nullable|string|max:50',
            'is_active'    => 'boolean',
            'sort_order'   => 'nullable|integer',
            'images.*'     => 'nullable|image|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);

        $project = PortfolioProject::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $this->media->upload($file, 'portfolio');
                PortfolioImage::create([
                    'portfolio_project_id' => $project->id,
                    'path'       => $path,
                    'alt'        => $project->client_name,
                    'role'       => $i === 0 ? 'hero' : 'secondary',
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio project created.');
    }

    public function edit(PortfolioProject $portfolio)
    {
        $portfolio->load('images');
        return view('admin.portfolio.edit', compact('portfolio'));
    }

    public function update(Request $request, PortfolioProject $portfolio)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'client_name'  => 'required|string|max:255',
            'client_badge' => 'nullable|string|max:255',
            'category'     => 'required|string|max:100',
            'description'  => 'nullable|string',
            'cta_text'     => 'nullable|string|max:100',
            'layout_type'  => 'nullable|string|max:50',
            'gallery_type' => 'nullable|string|max:50',
            'is_active'    => 'boolean',
            'sort_order'   => 'nullable|integer',
        ]);

        $portfolio->update($validated);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio project updated.');
    }

    public function destroy(PortfolioProject $portfolio)
    {
        foreach ($portfolio->images as $img) {
            $this->media->delete($img->path);
        }
        $portfolio->delete();

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio project deleted.');
    }

    public function uploadImage(Request $request, PortfolioProject $portfolio)
    {
        $request->validate(['image' => 'required|image|max:10240', 'role' => 'nullable|string']);

        $path = $this->media->upload($request->file('image'), 'portfolio');
        $image = PortfolioImage::create([
            'portfolio_project_id' => $portfolio->id,
            'path'       => $path,
            'alt'        => $portfolio->client_name,
            'role'       => $request->input('role', 'secondary'),
            'sort_order' => $portfolio->images()->count(),
        ]);

        return response()->json(['id' => $image->id, 'url' => $this->media->url($path)]);
    }

    public function deleteImage(PortfolioProject $portfolio, PortfolioImage $image)
    {
        $this->media->delete($image->path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
