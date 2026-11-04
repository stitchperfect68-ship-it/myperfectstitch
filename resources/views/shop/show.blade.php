@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->name) . ' - My Perfect Stitch')
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->short_description ?? $product->description ?? ''), 155))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/shop.css') }}"/>
@endpush

@section('content')
@php
  $images = $product->images;
  $firstImage = $images->first();
  $firstPath = $firstImage?->path;
  $mainSrc = $firstPath ? (str_starts_with($firstPath, 'assets/') ? asset($firstPath) : \Illuminate\Support\Facades\Storage::disk('public')->url($firstPath)) : '';
  $whatsappMessage = urlencode('Hi, I am interested in ' . $product->name . ' (' . route('shop.show', $product) . ')');
@endphp

<section class="pd-page">
  <div class="pd-container">
    <nav class="pd-breadcrumb" aria-label="Breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span>/</span>
      <a href="{{ route('shop.index') }}">Shop</a>
      @if ($product->category)
        <span>/</span>
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
      @endif
      @if ($product->subcategory)
        <span>/</span>
        <a href="{{ route('shop.index', ['subcategory' => $product->subcategory->slug]) }}">{{ $product->subcategory->name }}</a>
      @endif
      <span>/</span>
      <span style="color:#100736;font-weight:600">{{ $product->name }}</span>
    </nav>

    <div class="pd-layout">
      <div>
        <div class="pd-gallery-main">
          @if ($mainSrc)
            <img id="pdMainImage" src="{{ $mainSrc }}" alt="{{ $product->name }}"/>
          @else
            <div class="pd-placeholder"><i class="fas fa-image"></i></div>
          @endif
        </div>

        @if ($images->count() > 1)
          <div class="pd-thumbs">
            @foreach ($images as $image)
              @php
                $thumbSrc = str_starts_with($image->path, 'assets/') ? asset($image->path) : \Illuminate\Support\Facades\Storage::disk('public')->url($image->path);
              @endphp
              <button type="button" class="pd-thumb {{ $loop->first ? 'active' : '' }}" data-src="{{ $thumbSrc }}" onclick="selectProductImage(this)" aria-label="View image {{ $loop->iteration }}">
                <img src="{{ $thumbSrc }}" alt="{{ $image->alt ?? $product->name }}"/>
              </button>
            @endforeach
          </div>
        @endif
      </div>

      <div>
        @if ($product->category)
          <div class="pd-category">{{ $product->category->name }}@if ($product->subcategory) &middot; {{ $product->subcategory->name }}@endif</div>
        @endif

        <h1 class="pd-title">{{ $product->name }}</h1>

        @if ($product->tag_label)
          <div class="product-tag {{ $product->tag_type ?? 'tag-std' }}" style="position:static;display:inline-block;margin-bottom:12px">{{ $product->tag_label }}</div>
        @endif

        @if ($product->price > 0)
          <div class="pd-price">{{ $product->formatted_price }}</div>
        @else
          <div class="pd-price" style="font-size:1.1rem;color:#888;font-style:italic">Price on request</div>
        @endif

        <div class="stock-flag {{ $product->isInStock() ? 'in' : 'out' }}">
          <i class="fas {{ $product->isInStock() ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
          {{ $product->isInStock() ? 'In stock' : 'Out of stock' }}
          @if ($product->track_stock && $product->isInStock())
            <span style="color:#888;font-weight:500;text-transform:none;letter-spacing:0">&middot; {{ $product->stock_qty }} available</span>
          @endif
        </div>

        @if ($product->short_description)
          <p class="pd-short">{{ $product->short_description }}</p>
        @endif

        <div class="pd-divider"></div>

        <div class="pd-buy">
          @if ($product->price > 0 && $product->isInStock())
            <form method="POST" action="{{ route('cart.add', $product) }}" class="pd-buy" style="margin:0">
              @csrf
              <div class="pd-qty">
                <button type="button" onclick="adjustProductQty(-1)" aria-label="Decrease quantity">&minus;</button>
                <input type="number" id="pdQty" name="quantity" value="1" min="1" max="{{ $product->track_stock ? $product->stock_qty : 999 }}" readonly/>
                <button type="button" onclick="adjustProductQty(1)" aria-label="Increase quantity">+</button>
              </div>
              <button type="submit" class="pd-add"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
            </form>
          @elseif ($product->price <= 0)
            <button type="button" class="pd-quote" onclick="openQuoteModal()"><i class="fas fa-pen"></i> Request a Quote</button>
          @else
            <button type="button" class="pd-add" disabled><i class="fas fa-times-circle"></i> Out of Stock</button>
          @endif

          <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" target="_blank" rel="noopener" class="pd-whatsapp">
            <i class="fab fa-whatsapp"></i> Ask on WhatsApp
          </a>
        </div>

        @if ($product->description)
          <div class="pd-divider"></div>
          <div class="pd-desc">{!! nl2br(e($product->description)) !!}</div>
        @endif

        <div class="pd-meta">
          <div><strong>Category:</strong> {{ $product->category?->name ?? 'Uncategorised' }}</div>
          @if ($product->subcategory)
            <div><strong>Collection:</strong> {{ $product->subcategory->name }}</div>
          @endif
          <div><strong>Made in:</strong> Lusaka, Zambia</div>
        </div>
      </div>
    </div>

    @if ($related->isNotEmpty())
      <div class="pd-related">
        <h2>You may also <em>like</em></h2>
        <div class="products-grid">
          @foreach ($related as $item)
            @php
              $img = $item->primary_image_path;
              $imgSrc = $img ? (str_starts_with($img, 'assets/') ? asset($img) : \Illuminate\Support\Facades\Storage::disk('public')->url($img)) : '';
            @endphp
            <article class="product-card">
              @if ($item->tag_label)
                <div class="product-tag {{ $item->tag_type ?? 'tag-std' }}">{{ $item->tag_label }}</div>
              @endif
              <a href="{{ route('shop.show', $item) }}" class="product-img-wrap">
                @if ($imgSrc)
                  <img src="{{ $imgSrc }}" alt="{{ $item->name }}" loading="lazy"/>
                @endif
                <span class="product-overlay"><span class="overlay-btn">View Details</span></span>
              </a>
              <div class="product-info">
                <h3><a href="{{ route('shop.show', $item) }}">{{ $item->name }}</a></h3>
                <div class="product-desc">{{ $item->short_description ?? $item->subcategory?->name }}</div>
                <div class="product-footer">
                  @if ($item->price > 0)
                    <span class="price">{{ $item->formatted_price }}</span>
                    <a href="{{ route('shop.show', $item) }}" class="buy-btn">View</a>
                  @else
                    <span class="price-req">Price on request</span>
                    <a href="{{ route('shop.show', $item) }}" class="buy-btn">Details</a>
                  @endif
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</section>
@endsection

@section('scripts')
<script>
  function selectProductImage(button) {
    const main = document.getElementById('pdMainImage');
    if (!main) return;
    main.src = button.dataset.src;
    document.querySelectorAll('.pd-thumb').forEach(function (thumb) {
      thumb.classList.toggle('active', thumb === button);
    });
  }

  function adjustProductQty(delta) {
    const input = document.getElementById('pdQty');
    if (!input) return;
    const min = parseInt(input.min || '1', 10);
    const max = parseInt(input.max || '999', 10);
    const next = Math.min(max, Math.max(min, parseInt(input.value || '1', 10) + delta));
    input.value = next;
  }
</script>
@endsection
