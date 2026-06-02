<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|string|max:100',
            'quantity'     => 'nullable|integer|min:1',
            'budget'       => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:2000',
            'deadline'     => 'nullable|string|max:100',
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:30',
            'email'        => 'nullable|email|max:255',
        ]);

        $validated['ref'] = Quote::generateRef();

        Quote::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'ref' => $validated['ref']]);
        }

        return back()->with('success', 'Your quote request has been received! We\'ll get back to you shortly.');
    }
}
