@extends('layouts.admin')
@section('title','Products')
@section('topbar_title','Products')

@section('content')
<div class="page-header">
  <div><h1>Products</h1><p>Manage your product catalogue</p></div>
  <a href="{{ route('admin.products.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Product</a>
</div>

<div class="card">
  <div class="card-header">
    <form class="admin-search" method="GET">
      <input type="text" name="search" class="form-input" placeholder="Search products…" value="{{ request('search') }}" style="max-width:280px"/>
      <select name="category" class="filter-select">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i> Filter</button>
      @if(request()->hasAny(['search','category']))<a href="{{ route('admin.products.index') }}" class="btn btn-sm" style="color:#888">Clear</a>@endif
    </form>
    <span style="font-size:.82rem;color:#888">{{ $products->total() }} products</span>
  </div>
  <div style="overflow-x:auto">
    <table class="admin-table">
      <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($products as $product)
          <tr>
            <td>
              @php $img = $product->primary_image_path; @endphp
              @if($img)
                <img src="{{ str_starts_with($img,'assets/') ? asset($img) : \Illuminate\Support\Facades\Storage::disk('public')->url($img) }}" style="width:48px;height:48px;object-fit:cover;border-radius:4px;border:2px solid #e8e4f0"/>
              @else
                <div style="width:48px;height:48px;background:#f0f2f5;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#ccc"><i class="fas fa-image"></i></div>
              @endif
            </td>
            <td>
              <div style="font-weight:600;color:#100736">{{ $product->name }}</div>
              @if($product->tag_label)<span class="badge badge-yellow">{{ $product->tag_label }}</span>@endif
            </td>
            <td>
              <div>{{ $product->category?->name }}</div>
              @if($product->subcategory)<div style="font-size:.75rem;color:#888">{{ $product->subcategory->name }}</div>@endif
            </td>
            <td style="font-weight:700">{{ $product->price > 0 ? $product->formatted_price : 'On Request' }}</td>
            <td>
              @if($product->track_stock)
                <span class="badge {{ $product->stock_qty <= 5 ? 'badge-red' : 'badge-green' }}">{{ $product->stock_qty }}</span>
              @else
                <span class="badge badge-gray">Unlimited</span>
              @endif
            </td>
            <td>
              <label class="switch" title="Toggle active">
                <input type="checkbox" {{ $product->is_active ? 'checked' : '' }} onchange="toggleActive({{ $product->id }},this)"/>
                <span class="switch-slider"></span>
              </label>
            </td>
            <td>
              <a href="{{ route('admin.products.edit',$product) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
              <form method="POST" action="{{ route('admin.products.destroy',$product) }}" style="display:inline" onsubmit="return confirm('Delete this product?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:#888">No products found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination-wrap">{{ $products->withQueryString()->links() }}</div>
</div>
@endsection
