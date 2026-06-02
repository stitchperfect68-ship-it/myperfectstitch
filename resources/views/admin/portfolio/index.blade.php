@extends('layouts.admin')
@section('title','Portfolio')
@section('topbar_title','Portfolio Projects')

@section('content')
<div class="page-header">
  <div><h1>Portfolio Projects</h1><p>Client projects shown on the portfolio page</p></div>
  <a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Project</a>
</div>

<div class="card">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Project</th><th>Client</th><th>Category</th><th>Images</th><th>Status</th><th>Sort</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($projects as $project)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                @php $hero = $project->images->where('role','hero')->first() ?? $project->images->first(); @endphp
                @if($hero)
                  @php
                    $src = str_starts_with($hero->path,'assets/')
                      ? asset($hero->path)
                      : \Illuminate\Support\Facades\Storage::disk('public')->url($hero->path);
                  @endphp
                  <img src="{{ $src }}" alt="" style="width:52px;height:36px;object-fit:cover;border-radius:4px;border:1px solid #eee"/>
                @else
                  <div style="width:52px;height:36px;background:#f0f2f5;border-radius:4px;display:flex;align-items:center;justify-content:center"><i class="fas fa-image" style="color:#ccc;font-size:.8rem"></i></div>
                @endif
                <div>
                  <div style="font-weight:600;font-size:.88rem">{!! $project->title !!}</div>
                </div>
              </div>
            </td>
            <td style="font-size:.85rem;color:#555">{{ $project->client_name }}</td>
            <td>
              <span class="badge badge-blue">{{ ucfirst($project->category) }}</span>
            </td>
            <td style="font-size:.82rem;color:#888">{{ $project->images->count() }} image{{ $project->images->count() !== 1 ? 's' : '' }}</td>
            <td><span class="badge {{ $project->is_active ? 'badge-green' : 'badge-gray' }}">{{ $project->is_active ? 'Active' : 'Hidden' }}</span></td>
            <td style="color:#888;font-size:.85rem">{{ $project->sort_order ?? 0 }}</td>
            <td>
              <div style="display:flex;gap:6px">
                <a href="{{ route('admin.portfolio.edit', $project) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('admin.portfolio.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:#888">No portfolio projects yet. <a href="{{ route('admin.portfolio.create') }}">Add one</a></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($projects->hasPages())
    <div class="pagination-wrap">{{ $projects->links() }}</div>
  @endif
</div>
@endsection
