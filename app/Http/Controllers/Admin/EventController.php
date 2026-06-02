<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index()
    {
        $events = Event::with('images')->orderBy('sort_order')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'tag'            => 'nullable|string|max:255',
            'event_type'     => 'required|string|max:100',
            'description'    => 'nullable|string',
            'gallery_layout' => 'nullable|string|max:20',
            'text_first'     => 'boolean',
            'event_date'     => 'nullable|date',
            'is_active'      => 'boolean',
            'sort_order'     => 'nullable|integer',
            'images.*'       => 'nullable|image|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);

        $event = Event::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $this->media->upload($file, 'events');
                EventImage::create([
                    'event_id'   => $event->id,
                    'path'       => $path,
                    'alt'        => $event->title,
                    'role'       => $i === 0 ? 'hero' : 'secondary',
                    'sort_order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event)
    {
        $event->load('images');
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'tag'            => 'nullable|string|max:255',
            'event_type'     => 'required|string|max:100',
            'description'    => 'nullable|string',
            'gallery_layout' => 'nullable|string|max:20',
            'text_first'     => 'boolean',
            'event_date'     => 'nullable|date',
            'is_active'      => 'boolean',
            'sort_order'     => 'nullable|integer',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        foreach ($event->images as $img) {
            $this->media->delete($img->path);
        }
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    public function uploadImage(Request $request, Event $event)
    {
        $request->validate(['image' => 'required|image|max:10240']);

        $path = $this->media->upload($request->file('image'), 'events');
        $image = EventImage::create([
            'event_id'   => $event->id,
            'path'       => $path,
            'alt'        => $event->title,
            'role'       => $event->images()->count() === 0 ? 'hero' : 'secondary',
            'sort_order' => $event->images()->count(),
        ]);

        return response()->json(['id' => $image->id, 'url' => $this->media->url($path)]);
    }

    public function deleteImage(Event $event, EventImage $image)
    {
        $this->media->delete($image->path);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
