@extends('layouts.app')

@section('title', 'Shop - My Perfect Stitch')
@section('meta_description', 'Browse custom bags and bespoke furniture made in Lusaka, Zambia. Shop the My Perfect Stitch collection.')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}"/>
@endpush

@section('content')
<div class="shop-hero">
  <h1>Shop our <em>Pieces</em></h1>
  <p>Custom bags and bespoke furniture, crafted in Lusaka.</p>
</div>

<section class="shop-page">
  <div class="shop-container">
    <div class="shop-layout">
      <aside class="shop-filter-card">
        <h2><i class="fas fa-sliders-h"></i> Filters</h2>
        <form method="GET" action="{{ route('shop.index') }}">
          @if (request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}"/>
          @endif
          <div class="filter-group">
            <label for="q">Search</label>
            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Search products..."/>
          </div>

          <div class="filter-group">
            <label for="categoryFilter">Category</label>
            <select name="category" id="categoryFilter">
              <option value="">All categories</option>
              @foreach ($categories as $parent)
                <option value="{{ $parent->slug }}" @selected(request('category') === $parent->slug)>{{ $parent->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="filter-group">
            <label for="subcategoryFilter">Subcategory</label>
            <select name="subcategory" id="subcategoryFilter">
              <option value="">All subcategories</option>
              @foreach ($categories as $parent)
                @if ($parent->children->isNotEmpty())
                  <optgroup label="{{ $parent->name }}" data-parent="{{ $parent->slug }}">
                    @foreach ($parent->children as $child)
                      <option value="{{ $child->slug }}" data-parent="{{ $parent->slug }}" @selected(request('subcategory') === $child->slug)>{{ $child->name }}</option>
                    @endforeach
                  </optgroup>
                @endif
              @endforeach
            </select>
          </div>

          <div class="filter-group">
            <label>Price (ZMW)</label>
            <div class="filter-price-row">
              <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0" step="1"/>
              <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0" step="1"/>
            </div>
          </div>

          <label class="filter-check">
            <input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock'))/>
            In stock only
          </label>

          <div class="filter-actions">
            <button type="submit" class="filter-apply">Apply Filters</button>
            <a href="{{ route('shop.index') }}" class="filter-reset">Reset all</a>
          </div>
        </form>
      </aside>

      <div>
        <div class="shop-results-head">
          <p><strong>{{ $products->total() }}</strong> {{ \Illuminate\Support\Str::plural('product', $products->total()) }} found</p>
          <form method="GET" action="{{ route('shop.index') }}" class="shop-sort">
            @foreach (request()->except(['sort', 'page']) as $key => $value)
              @if (is_array($value))
                @foreach ($value as $item)
                  <input type="hidden" name="{{ $key }}[]" value="{{ $item }}"/>
                @endforeach
              @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}"/>
              @endif
            @endforeach
            <label for="sort">Sort</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
              <option value="default" @selected(request('sort', 'default') === 'default')>Recommended</option>
              <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
              <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
              <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
              <option value="name" @selected(request('sort') === 'name')>Name: A-Z</option>
            </select>
          </form>
        </div>

        @if ($products->isEmpty())
          <div class="shop-empty">
            <i class="fas fa-search"></i>
            <h3>No products found</h3>
            <p>Try adjusting your filters or search terms.</p>
            <a href="{{ route('shop.index') }}" class="buy-btn">Clear Filters</a>
          </div>
        @else
          <div class="products-grid">
            @foreach ($products as $product)
              @php
                $img = $product->primary_image_path;
                $imgSrc = $img ? (str_starts_with($img, 'assets/') ? asset($img) : \Illuminate\Support\Facades\Storage::disk('public')->url($img)) : '';
              @endphp
              <article class="product-card">
                @if ($product->tag_label)
                  <div class="product-tag {{ $product->tag_type ?? 'tag-std' }}">{{ $product->tag_label }}</div>
                @endif
                <a href="{{ route('shop.show', $product) }}" class="product-img-wrap">
                  @if ($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $product->name }}" loading="lazy"/>
                  @endif
                  <span class="product-overlay"><span class="overlay-btn">View Details</span></span>
                </a>
                <div class="product-info">
                  <h3><a href="{{ route('shop.show', $product) }}">{{ $product->name }}</a></h3>
                  <div class="product-desc">{{ $product->short_description ?? $product->subcategory?->name ?? $product->category?->name }}</div>
                  <div class="product-footer">
                    @if ($product->price > 0)
                      <span class="price">{{ $product->formatted_price }}</span>
                      @if ($product->isInStock())
                        <form method="POST" action="{{ route('cart.add', $product) }}">
                          @csrf
                          <input type="hidden" name="quantity" value="1"/>
                          <button type="submit" class="buy-btn"><i class="fas fa-shopping-bag"></i> Add</button>
                        </form>
                      @else
                        <button type="button" class="buy-btn" disabled>Out of stock</button>
                      @endif
                    @else
                      <span class="price-req">Price on request</span>
                      <a href="{{ route('shop.show', $product) }}" class="buy-btn">Details</a>
                    @endif
                  </div>
                </div>
              </article>
            @endforeach
          </div>

          @include('partials.pagination', ['paginator' => $products])
        @endif
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
  (function () {
    const category = document.getElementById('categoryFilter');
    const subcategory = document.getElementById('subcategoryFilter');
    if (!category || !subcategory) return;

    function syncSubcategories() {
      const selected = category.value;
      Array.from(subcategory.options).forEach(function (option) {
        if (!option.value) return;
        const matches = !selected || option.dataset.parent === selected;
        option.hidden = !matches;
        option.disabled = !matches;
      });
      const current = subcategory.options[subcategory.selectedIndex];
      if (current && current.value && current.disabled) {
        subcategory.value = '';
      }
    }

    category.addEventListener('change', syncSubcategories);
    syncSubcategories();
  })();
</script>
@endsection
