@extends('layouts.admin')
@section('title','Clients')
@section('topbar_title','Clients')

@section('content')
<div class="page-header">
  <div><h1>Clients</h1><p>Institutional and corporate clients displayed on the website</p></div>
  <a href="{{ route('admin.clients.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Client</a>
</div>

<div class="card">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Name</th><th>Industry</th><th>Website</th><th>Status</th><th>Sort</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($clients as $client)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                @if($client->logo_path)
                  <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($client->logo_path) }}" alt="{{ $client->name }}" style="width:32px;height:32px;border-radius:4px;object-fit:contain;background:#f5f5f5;padding:2px;border:1px solid #eee"/>
                @else
                  <div style="width:32px;height:32px;border-radius:4px;background:#f0f2f5;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;color:#888">{{ strtoupper(substr($client->name,0,2)) }}</div>
                @endif
                <span style="font-weight:600">{{ $client->name }}</span>
              </div>
            </td>
            <td style="color:#555;font-size:.85rem">{{ $client->industry ?? '—' }}</td>
            <td>
              @if($client->website)
                <a href="{{ $client->website }}" target="_blank" style="color:#100736;font-size:.82rem">{{ parse_url($client->website, PHP_URL_HOST) }}</a>
              @else
                <span style="color:#ccc">—</span>
              @endif
            </td>
            <td><span class="badge {{ $client->is_active ? 'badge-green' : 'badge-gray' }}">{{ $client->is_active ? 'Active' : 'Hidden' }}</span></td>
            <td style="color:#888;font-size:.85rem">{{ $client->sort_order ?? 0 }}</td>
            <td>
              <div style="display:flex;gap:6px">
                <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" onsubmit="return confirm('Delete {{ addslashes($client->name) }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" style="text-align:center;padding:32px;color:#888">No clients yet. <a href="{{ route('admin.clients.create') }}">Add one</a></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($clients->hasPages())
    <div class="pagination-wrap">{{ $clients->links() }}</div>
  @endif
</div>
@endsection
