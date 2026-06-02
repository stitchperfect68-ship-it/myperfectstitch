<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\MediaService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index()
    {
        $clients = Client::orderBy('sort_order')->paginate(20);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'logo'       => 'nullable|image|max:5120',
            'website'    => 'nullable|url|max:255',
            'industry'   => 'nullable|string|max:255',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $this->media->upload($request->file('logo'), 'clients');
        }

        Client::create($validated);

        return redirect()->route('admin.clients.index')->with('success', 'Client added.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'logo'       => 'nullable|image|max:5120',
            'website'    => 'nullable|url|max:255',
            'industry'   => 'nullable|string|max:255',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('logo')) {
            $this->media->delete($client->logo_path);
            $validated['logo_path'] = $this->media->upload($request->file('logo'), 'clients');
        }

        $client->update($validated);

        return redirect()->route('admin.clients.index')->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $this->media->delete($client->logo_path);
        $client->delete();

        return redirect()->route('admin.clients.index')->with('success', 'Client deleted.');
    }
}
