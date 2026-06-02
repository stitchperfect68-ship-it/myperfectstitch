<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Services\MediaService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(private MediaService $media) {}

    public function index()
    {
        $members = TeamMember::orderBy('sort_order')->paginate(15);
        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'bio'        => 'nullable|string',
            'photo'      => 'nullable|image|max:5120',
            'email'      => 'nullable|email',
            'linkedin'   => 'nullable|string|max:255',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->media->upload($request->file('photo'), 'team');
        }

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member added.');
    }

    public function edit(TeamMember $team)
    {
        return view('admin.team.edit', compact('team'));
    }

    public function update(Request $request, TeamMember $team)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|string|max:255',
            'bio'        => 'nullable|string',
            'photo'      => 'nullable|image|max:5120',
            'email'      => 'nullable|email',
            'linkedin'   => 'nullable|string|max:255',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {
            $this->media->delete($team->photo);
            $validated['photo'] = $this->media->upload($request->file('photo'), 'team');
        }

        $team->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated.');
    }

    public function destroy(TeamMember $team)
    {
        $this->media->delete($team->photo);
        $team->delete();

        return redirect()->route('admin.team.index')->with('success', 'Team member deleted.');
    }
}
