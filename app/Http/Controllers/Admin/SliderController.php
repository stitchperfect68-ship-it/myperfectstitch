<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Services\MediaService;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'                => 'required|image|max:10240',
            'heading'              => 'nullable|string|max:255',
            'subheading'           => 'nullable|string|max:255',
            'description'          => 'nullable|string|max:500',
            'btn_text'             => 'nullable|string|max:100',
            'btn_url'              => 'nullable|string|max:255',
            'btn_secondary_text'   => 'nullable|string|max:100',
            'btn_secondary_url'    => 'nullable|string|max:255',
            'is_active'            => 'boolean',
            'sort_order'           => 'nullable|integer',
        ]);

        $path = $this->media->upload($request->file('image'), 'sliders');

        Slider::create(array_merge($request->except('image'), ['image_path' => $path]));

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'image'                => 'nullable|image|max:10240',
            'heading'              => 'nullable|string|max:255',
            'subheading'           => 'nullable|string|max:255',
            'description'          => 'nullable|string|max:500',
            'btn_text'             => 'nullable|string|max:100',
            'btn_url'              => 'nullable|string|max:255',
            'btn_secondary_text'   => 'nullable|string|max:100',
            'btn_secondary_url'    => 'nullable|string|max:255',
            'is_active'            => 'boolean',
            'sort_order'           => 'nullable|integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $this->media->delete($slider->image_path);
            $data['image_path'] = $this->media->upload($request->file('image'), 'sliders');
        }

        $slider->update($data);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated.');
    }

    public function destroy(Slider $slider)
    {
        $this->media->delete($slider->image_path);
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted.');
    }
}
