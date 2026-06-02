@extends('layouts.admin')
@section('title','Gallery')
@section('topbar_title','Gallery')

@section('content')
<div class="page-header">
  <div><h1>Gallery</h1><p>{{ $items->total() }} items across {{ $categories->count() }} categories</p></div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Item</a>
  </div>
</div>

{{-- Bulk Upload Form --}}
<div class="card" style="margin-bottom:20px">
  <div class="card-header"><h3><i class="fas fa-upload" style="color:#F9B040;margin-right:6px"></i>Bulk Upload</h3></div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.gallery.bulk-upload') }}" enctype="multipart/form-data" style="display:flex;align-items:end;gap:12px;flex-wrap:wrap">
      @csrf
      <div class="form-group" style="margin:0">
        <label class="form-label">Category</label>
        <select name="category" class="form-select" required>
          @foreach(['bags','furniture','interior'] as $cat)<option>{{ $cat }}</option>@endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0">
        <label class="form-label">Images</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-input" required/>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload All</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <form class="admin-search" method="GET">
      <select name="category" class="filter-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $cat)<option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ ucfirst($cat) }}</option>@endforeach
      </select>
      @if(request('category'))<a href="{{ route('admin.gallery.index') }}" class="btn btn-sm" style="color:#888">Show All</a>@endif
    </form>
  </div>
  <div style="padding:20px;display:grid;grid-template-columns:repeat(6,1fr);gap:12px">
    @forelse($items as $item)
      @php $src = str_starts_with($item->path,'assets/') ? asset($item->path) : \Illuminate\Support\Facades\Storage::disk('public')->url($item->path); @endphp
      <div style="position:relative;border-radius:6px;overflow:hidden;aspect-ratio:1;border:2px solid #e8e4f0;group">
        <img src="{{ $src }}" style="width:100%;height:100%;object-fit:cover" loading="lazy"/>
        <div style="position:absolute;inset:0;background:rgba(16,7,54,.7);opacity:0;transition:.2s;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px" class="overlay" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
          <span style="color:#F9B040;font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em">{{ $item->category }}</span>
          <div style="display:flex;gap:6px">
            <a href="{{ route('admin.gallery.edit',$item) }}" style="background:#F9B040;color:#100736;padding:4px 10px;border-radius:3px;font-size:.7rem;font-weight:700;text-decoration:none">Edit</a>
            <form method="POST" action="{{ route('admin.gallery.destroy',$item) }}" style="display:inline" onsubmit="return confirm('Delete?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:#ef4444;color:#fff;padding:4px 10px;border-radius:3px;font-size:.7rem;font-weight:700;border:none;cursor:pointer">Del</button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div style="grid-column:1/-1;text-align:center;padding:40px;color:#888">No gallery items. Upload some!</div>
    @endforelse
  </div>
  <div class="pagination-wrap">{{ $items->withQueryString()->links() }}</div>
</div>
@endsection
