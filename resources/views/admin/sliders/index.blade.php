@extends('layouts.admin')
@section('title','Sliders')
@section('topbar_title','Hero Sliders')

@section('content')
<div class="page-header">
  <div><h1>Hero Sliders</h1><p>Manage the homepage hero slideshow</p></div>
  <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Slide</a>
</div>

<div class="card">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Slide</th><th>Heading</th><th>Subheading</th><th>Button</th><th>Status</th><th>Sort</th><th></th></tr>
      </thead>
      <tbody>
        @forelse($sliders as $slider)
          <tr>
            <td>
              @php
                $imgSrc = $slider->image_path
                  ? (str_starts_with($slider->image_path,'assets/')
                      ? asset($slider->image_path)
                      : \Illuminate\Support\Facades\Storage::disk('public')->url($slider->image_path))
                  : null;
              @endphp
              @if($imgSrc)
                <img src="{{ $imgSrc }}" alt="" style="width:72px;height:44px;object-fit:cover;border-radius:4px;border:1px solid #eee"/>
              @else
                <div style="width:72px;height:44px;background:#f0f2f5;border-radius:4px;display:flex;align-items:center;justify-content:center"><i class="fas fa-image" style="color:#ccc"></i></div>
              @endif
            </td>
            <td style="font-weight:600;max-width:180px">{{ $slider->heading ?? '—' }}</td>
            <td style="color:#555;font-size:.85rem;max-width:160px">{{ $slider->subheading ?? '—' }}</td>
            <td style="font-size:.82rem">
              @if($slider->btn_text)
                <span style="background:#f0f2f5;padding:3px 8px;border-radius:3px;font-weight:600;color:#100736">{{ $slider->btn_text }}</span>
              @else
                <span style="color:#ccc">—</span>
              @endif
            </td>
            <td><span class="badge {{ $slider->is_active ? 'badge-green' : 'badge-gray' }}">{{ $slider->is_active ? 'Active' : 'Hidden' }}</span></td>
            <td style="color:#888;font-size:.85rem">{{ $slider->sort_order }}</td>
            <td>
              <div style="display:flex;gap:6px">
                <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" onsubmit="return confirm('Delete this slide?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:#888">No slides yet. <a href="{{ route('admin.sliders.create') }}">Add one</a></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
