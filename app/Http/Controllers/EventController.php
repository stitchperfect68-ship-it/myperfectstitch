<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::active()->with('images')->get();
        $types = $events->pluck('event_type')->unique()->values();

        return view('events', compact('events', 'types'));
    }
}
