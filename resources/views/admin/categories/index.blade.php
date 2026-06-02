@extends('layouts.admin')
@section('title','Categories')
@section('topbar_title','Product Categories')

@section('content')
<div class="page-header">
  <div><h1>Categories</h1><p>Manage product parent categories and subcategories</p></div>
  <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</a>
</div>

<div class="card">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Slug</th>
          <th>Parent</th>
          <th>Sub-categories</th>
          <th>Status</th>
          <th>Sort</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $cat)
          <tr>
            <td style="font-weight:600;color:#100736">{{ $cat->name }}</td>
            <td style="font-family:monospace;font-size:.8rem;color:#888">{{ $cat->slug }}</td>
            <td>{{ $cat->parent?->name ?? '—' }}</td>
            <td>
              @if($cat->children->count())
                <span class="badge badge-blue">{{ $cat->children->count() }} subs</span>
              @else
                <span style="color:#ccc;font-size:.8rem">—</span>
              @endif
            </td>
            <td>
              <span class="badge {{ $cat->is_active ? 'badge-green' : 'badge-gray' }}">
                {{ $cat->is_active ? 'Active' : 'Hidden' }}
              </span>
            </td>
            <td style="color:#888;font-size:.85rem">{{ $cat->sort_order ?? 0 }}</td>
            <td>
              <div style="display:flex;gap:6px">
                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete {{ $cat->name }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:#888">No categories yet. <a href="{{ route('admin.categories.create') }}">Add one</a></td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($categories->hasPages())
    <div class="pagination-wrap">{{ $categories->links() }}</div>
  @endif
</div>
@endsection
