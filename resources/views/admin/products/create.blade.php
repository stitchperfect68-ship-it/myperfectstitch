@extends('layouts.admin')
@section('title','Add Product')
@section('topbar_title','Add Product')

@section('content')
<div class="page-header">
  <div><h1>Add Product</h1><p>Create a new product listing</p></div>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
  @csrf
  <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start">
    <div>
      <div class="card">
        <div class="card-header"><h3>Product Details</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">Product Name *</label><input type="text" name="name" class="form-input" required value="{{ old('name') }}"/></div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Parent Category *</label>
              <select name="category_id" class="form-select" required id="catSelect" onchange="loadSubs(this.value)">
                <option value="">Select category…</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Subcategory</label>
              <select name="subcategory_id" class="form-select" id="subSelect">
                <option value="">Select parent first…</option>
              </select>
            </div>
          </div>
          <div class="form-group"><label class="form-label">Short Description</label><input type="text" name="short_description" class="form-input" value="{{ old('short_description') }}" placeholder="Brief one-liner for product cards"/></div>
          <div class="form-group"><label class="form-label">Full Description</label><textarea name="description" class="form-textarea" rows="5">{{ old('description') }}</textarea></div>
          <div class="form-row">
            <div class="form-group"><label class="form-label">Tag Type</label><select name="tag_type" class="form-select"><option value="">None</option><option value="tag-std" {{ old('tag_type')==='tag-std'?'selected':'' }}>Standard (Gold)</option><option value="tag-corp" {{ old('tag_type')==='tag-corp'?'selected':'' }}>Corporate (Navy)</option></select></div>
            <div class="form-group"><label class="form-label">Tag Label</label><input type="text" name="tag_label" class="form-input" value="{{ old('tag_label') }}" placeholder="e.g. Popular, New Arrival"/></div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><h3>Product Images</h3></div>
        <div class="card-body">
          <div class="upload-area" onclick="document.getElementById('imageUpload').click()">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Click to upload images<br><span style="font-size:.72rem;color:#aaa">PNG, JPG up to 5MB each. First image = primary.</span></p>
          </div>
          <input type="file" id="imageUpload" name="images[]" multiple accept="image/*" style="display:none" onchange="previewImages(this)"/>
          <div class="img-preview-grid" id="imagePreview"></div>
        </div>
      </div>
    </div>

    <div>
      <div class="card">
        <div class="card-header"><h3>Pricing & Stock</h3></div>
        <div class="card-body">
          <div class="form-group"><label class="form-label">Price (ZMW)</label><input type="number" name="price" class="form-input" step="0.01" min="0" value="{{ old('price',0) }}" placeholder="0 = Price on Request"/></div>
          <div class="form-group"><label class="form-label">Stock Quantity</label><input type="number" name="stock_qty" class="form-input" min="0" value="{{ old('stock_qty',999) }}"/></div>
          <div class="form-group" style="display:flex;align-items:center;justify-content:space-between">
            <label class="form-label" style="margin:0">Track Stock</label>
            <label class="switch"><input type="checkbox" name="track_stock" value="1" {{ old('track_stock') ? 'checked' : '' }}/><span class="switch-slider"></span></label>
          </div>
          <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="{{ old('sort_order',0) }}"/></div>
        </div>
      </div>
      <div class="card">
        <div class="card-header"><h3>Visibility</h3></div>
        <div class="card-body">
          <div class="form-group" style="display:flex;align-items:center;justify-content:space-between"><label class="form-label" style="margin:0">Active (visible on site)</label><label class="switch"><input type="checkbox" name="is_active" value="1" {{ old('is_active',1)?'checked':'' }}/><span class="switch-slider"></span></label></div>
          <div class="form-group" style="display:flex;align-items:center;justify-content:space-between"><label class="form-label" style="margin:0">Featured Product</label><label class="switch"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured')?'checked':'' }}/><span class="switch-slider"></span></label></div>
        </div>
      </div>
      <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center"><i class="fas fa-save"></i> Save Product</button>
    </div>
  </div>
</form>
@endsection

@section('scripts')
<script>
const catSubs = @json($categories->mapWithKeys(fn($c) => [$c->id => $c->children->map(fn($s) => ['id'=>$s->id,'name'=>$s->name])]));
function loadSubs(catId) {
  const sel = document.getElementById('subSelect');
  sel.innerHTML = '<option value="">None</option>';
  (catSubs[catId] || []).forEach(s => sel.innerHTML += `<option value="${s.id}">${s.name}</option>`);
}
function previewImages(input) {
  const preview = document.getElementById('imagePreview');
  preview.innerHTML = '';
  [...input.files].forEach(f => {
    const reader = new FileReader();
    reader.onload = e => {
      preview.innerHTML += `<div class="img-preview-item"><img src="${e.target.result}"/></div>`;
    };
    reader.readAsDataURL(f);
  });
}
</script>
@endsection
