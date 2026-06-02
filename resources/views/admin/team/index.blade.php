@extends('layouts.admin')
@section('title','Team')
@section('topbar_title','Team Members')

@section('content')
<div class="page-header">
  <div><h1>Team Members</h1><p>Staff and team members shown on the website</p></div>
  <a href="{{ route('admin.team.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Member</a>
</div>

<div class="card">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Member</th><th>Role</th><th>Email</th><th>LinkedIn</th><th>Status</th><th>Sort</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($members as $member)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                @if($member->photo)
                  <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($member->photo) }}" alt="{{ $member->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid #f0f2f5"/>
                @else
                  <div style="width:36px;height:36px;border-radius:50%;background:#100736;display:flex;align-items:center;justify-content:center;color:#F9B040;font-weight:700;font-size:.85rem">{{ strtoupper(substr($member->name,0,1)) }}</div>
                @endif
                <span style="font-weight:600">{{ $member->name }}</span>
              </div>
            </td>
            <td style="color:#555;font-size:.85rem">{{ $member->role }}</td>
            <td style="font-size:.82rem">
              @if($member->email)
                <a href="mailto:{{ $member->email }}" style="color:#100736">{{ $member->email }}</a>
              @else
                <span style="color:#ccc">—</span>
              @endif
            </td>
            <td>
              @if($member->linkedin)
                <a href="{{ $member->linkedin }}" target="_blank" class="btn btn-sm btn-outline"><i class="fab fa-linkedin"></i></a>
              @else
                <span style="color:#ccc;font-size:.8rem">—</span>
              @endif
            </td>
            <td><span class="badge {{ $member->is_active ? 'badge-green' : 'badge-gray' }}">{{ $member->is_active ? 'Active' : 'Hidden' }}</span></td>
            <td style="color:#888;font-size:.85rem">{{ $member->sort_order ?? 0 }}</td>
            <td>
              <div style="display:flex;gap:6px">
                <a href="{{ route('admin.team.edit', $member) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('admin.team.destroy', $member) }}" onsubmit="return confirm('Remove {{ addslashes($member->name) }} from team?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:#888">No team members yet. <a href="{{ route('admin.team.create') }}">Add one</a></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($members->hasPages())
    <div class="pagination-wrap">{{ $members->links() }}</div>
  @endif
</div>
@endsection
