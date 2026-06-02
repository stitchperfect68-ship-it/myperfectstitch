@extends('layouts.admin')
@section('title','Events')
@section('topbar_title','Events & Milestones')

@section('content')
<div class="page-header">
  <div><h1>Events & Milestones</h1><p>Awards, workshops, and milestones shown on the events page</p></div>
  <a href="{{ route('admin.events.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Event</a>
</div>

<div class="card">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Event</th><th>Type</th><th>Tag</th><th>Date</th><th>Images</th><th>Status</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($events as $event)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                @php $hero = $event->images->where('role','hero')->first() ?? $event->images->first(); @endphp
                @if($hero)
                  @php
                    $src = str_starts_with($hero->path,'assets/')
                      ? asset($hero->path)
                      : \Illuminate\Support\Facades\Storage::disk('public')->url($hero->path);
                  @endphp
                  <img src="{{ $src }}" alt="" style="width:52px;height:36px;object-fit:cover;border-radius:4px;border:1px solid #eee"/>
                @else
                  <div style="width:52px;height:36px;background:#f0f2f5;border-radius:4px;display:flex;align-items:center;justify-content:center"><i class="fas fa-calendar" style="color:#ccc;font-size:.8rem"></i></div>
                @endif
                <span style="font-weight:600;font-size:.88rem">{!! $event->title !!}</span>
              </div>
            </td>
            <td>
              @php $typeColors = ['award'=>'badge-yellow','iwd'=>'badge-purple','workshop'=>'badge-blue']; @endphp
              <span class="badge {{ $typeColors[$event->event_type] ?? 'badge-gray' }}">{{ ucfirst($event->event_type) }}</span>
            </td>
            <td style="font-size:.82rem;color:#555;max-width:160px">{{ $event->tag ?? '—' }}</td>
            <td style="font-size:.82rem;color:#888">{{ $event->event_date?->format('d M Y') ?? '—' }}</td>
            <td style="font-size:.82rem;color:#888">{{ $event->images->count() }} image{{ $event->images->count() !== 1 ? 's' : '' }}</td>
            <td><span class="badge {{ $event->is_active ? 'badge-green' : 'badge-gray' }}">{{ $event->is_active ? 'Active' : 'Hidden' }}</span></td>
            <td>
              <div style="display:flex;gap:6px">
                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Delete this event?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:#888">No events yet. <a href="{{ route('admin.events.create') }}">Add one</a></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($events->hasPages())
    <div class="pagination-wrap">{{ $events->links() }}</div>
  @endif
</div>
@endsection
