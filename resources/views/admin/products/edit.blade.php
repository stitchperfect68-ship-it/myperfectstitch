@extends('layouts.admin')
@section('title','Edit Product')
@section('topbar_title','Edit Product')

@section('content')
<div class="page-header">
  <div><h1>Edit: {{ $product->name }}</h1></div>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ route('admin.products.update',$product) }}" enctype="multipart/form-data">
  @csrf @method('PUT')
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start">
    <div>
      <div class="card">
        <div class="card-header"><h3>Product Details</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" class="form-input" required value="{{ old('name',$product->name) }}"/></div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Parent Category</label>
              <select name="category_id" class="form-select">
                <option value="">None</option>
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ $product->category_id==$cat->id?'selected':'' }}>{{ $cat->name }}</option>@endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Subcategory</label>
              <select name="subcategory_id" class="form-select">
                <option value="">None</option>
                @foreach($categories as $cat)
                  @foreach($cat->children as $sub)
                    <option value="{{ $sub->id }}" {{ $product->subcategory_id==$sub->id?'selected':'' }}>{{ $cat->name }} › {{ $sub->name }}</option>
                  @endforeach
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group"><label class="form-label">Short Description</label><input type="text" name="short_description" class="form-input" value="{{ old('short_description',$product->short_description) }}"/></div>
          <div class="form-group"><label class="form-label">Full Description</label><textarea name="description" class="form-textarea" rows="5">{{ old('description',$product->description) }}</textarea></div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">Tag Type</label><select name="tag_type" class="form-select"><option value="">None</option><option value="tag-std" {{ $product->tag_type==='tag-std'?'selected':'' }}>Standard (Gold)</option><option value="tag-corp" {{ $product->tag_type==='tag-corp'?'selected':'' }}>Corporate (Navy)</option></select></div>
            <div class="form-group"><label class="form-label">Tag Label</label><input type="text" name="tag_label" class="form-input" value="{{ old('tag_label',$product->tag_label) }}"/></div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3>Images</h3></div>
        <div class="card-body">
          @if($product->images->count())
            <div class="img-preview-grid" id="existingImages">
              @foreach($product->images as $img)
                @php $src = str_starts_with($img->path,'assets/') ? asset($img->path) : \Illuminate\Support\Facades\Storage::disk('public')->url($img->path); @endphp
                <div class="img-preview-item" id="img-{{ $img->id }}">
                  <img src="{{ $src }}"/>
                  @if($img->is_primary)<div style="position:absolute;bottom:0;left:0;right:0;background:rgba(249,176,64,.8);color:#100736;font-size:.55rem;font-weight:800;text-align:center;padding:2px">PRIMARY</div>@endif
                  <button type="button" class="img-preview-remove" onclick="deleteImage({{ $img->id }},'{{ $product->id }}')" title="Delete">×</button>
                </div>
              @endforeach
            </div>
          @endif
          <div class="upload-area" style="margin-top:12px" onclick="document.getElementById('newImages').click()">
            <i class="fas fa-plus"></i><p>Add More Images</p>
          </div>
          <input type="file" id="newImages" name="images[]" multiple accept="image/*" style="display:none" onchange="previewNew(this)"/>
          <div class="img-preview-grid" id="newPreview"></div>
        </div>
      </div>
    </div>

    <div>
      <div class="card">
        <div class="card-header"><h3>Pricing & Stock</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">Price (ZMW)</label><input type="number" name="price" class="form-input" step="0.01" min="0" value="{{ old('price',$product->price) }}"/></div>
          <div class="form-group"><label class="form-label">Stock Quantity</label><input type="number" name="stock_qty" class="form-input" min="0" value="{{ old('stock_qty',$product->stock_qty) }}"/></div>
          <div class="form-group" style="display:flex;align-items:center;justify-content:space-between"><label class="form-label" style="margin:0">Track Stock</label><label class="switch"><input type="checkbox" name="track_stock" value="1" {{ $product->track_stock?'checked':'' }}/><span class="switch-slider"></span></label></div>
          <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="{{ old('sort_order',$product->sort_order) }}"/></div>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h3>Visibility</h3></div>
        <div class="card-body">
          <div class="form-group" style="display:flex;align-items:center;justify-content:space-between"><label class="form-label" style="margin:0">Active</label><label class="switch"><input type="checkbox" name="is_active" value="1" {{ $product->is_active?'checked':'' }}/><span class="switch-slider"></span></label></div>
          <div class="form-group" style="display:flex;align-items:center;justify-content:space-between"><label class="form-label" style="margin:0">Featured</label><label class="switch"><input type="checkbox" name="is_featured" value="1" {{ $product->is_featured?'checked':'' }}/><span class="switch-slider"></span></label></div>
        </div>
      </div>
      <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center"><i class="fas fa-save"></i> Update Product</button>
    </div>
  </div>
</form>
@endsection

@section('scripts')
<script>
function deleteImage(imgId, productId) {
  if (!confirm('Delete this image?')) return;
  fetch(`/admin/products/${productId}/images/${imgId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
  }).then(() => document.getElementById('img-' + imgId)?.remove());
}
function previewNew(input) {
  const p = document.getElementById('newPreview');
  p.innerHTML = '';
  [...input.files].forEach(f => {
    const r = new FileReader();
    r.onload = e => p.innerHTML += `<div class="img-preview-item"><img src="${e.target.result}"/></div>`;
    r.readAsDataURL(f);
  });
}
</script>
@endsection
