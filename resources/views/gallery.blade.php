@extends('layouts.app')
@section('title', 'Gallery — My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/gallery.css') }}"/>
@endpush

@section('content')
<div class="page-hero">
  <div class="page-hero-label">Our Work</div>
  <h1>The <em>Gallery</em></h1>
</div>

<section class="gallery-section">
  <div style="max-width:1400px;margin:0 auto">
    <div class="filter-bar">
      <button class="filter-btn active" onclick="filterGallery('all',this)">
        All <span style="opacity:.5;font-weight:400">({{ $items->count() }})</span>
      </button>
      @foreach($categories as $cat)
        <button class="filter-btn" onclick="filterGallery('{{ $cat }}',this)">
          {{ ucfirst($cat) }} <span style="opacity:.5;font-weight:400">({{ $items->where('category',$cat)->count() }})</span>
        </button>
      @endforeach
    </div>

    <div class="gallery-grid" id="galleryGrid">
      @foreach($items as $item)
        @php
          $src = str_starts_with($item->path,'assets/') ? asset($item->path) : \Illuminate\Support\Facades\Storage::disk('public')->url($item->path);
        @endphp
        <div class="gallery-item" data-category="{{ $item->category }}" onclick="openLightbox(this)">
          <img src="{{ $src }}" alt="{{ $item->alt ?? ucfirst($item->category) }}" loading="lazy" data-src="{{ $src }}"/>
          <div class="gallery-item-overlay">
            <span class="gallery-item-label">{{ ucfirst($item->category) }}</span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
  <div class="lightbox-inner" onclick="event.stopPropagation()">
    <button class="lightbox-close" onclick="closeLightbox()">×</button>
    <img class="lightbox-img" id="lightboxImg" src="" alt=""/>
  </div>
  <button class="lightbox-nav lb-prev" onclick="lightboxNav(-1)"><i class="fas fa-chevron-left"></i></button>
  <button class="lightbox-nav lb-next" onclick="lightboxNav(1)"><i class="fas fa-chevron-right"></i></button>
</div>
@endsection

@section('scripts')
<script>
let lbItems = [], lbIdx = 0;
function filterGallery(cat, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.gallery-item').forEach(item => {
    item.style.display = (cat === 'all' || item.dataset.category === cat) ? '' : 'none';
  });
}
function openLightbox(el) {
  const visibles = [...document.querySelectorAll('.gallery-item')].filter(i => i.style.display !== 'none');
  lbItems = visibles.map(i => i.querySelector('img').src);
  lbIdx   = visibles.indexOf(el);
  showLightboxItem(lbIdx);
  document.getElementById('lightbox').classList.add('open');
}
function showLightboxItem(idx) {
  lbIdx = (idx + lbItems.length) % lbItems.length;
  document.getElementById('lightboxImg').src = lbItems[lbIdx];
}
function lightboxNav(dir) { showLightboxItem(lbIdx + dir); }
function closeLightbox() { document.getElementById('lightbox').classList.remove('open'); }
document.addEventListener('keydown', e => {
  if (!document.getElementById('lightbox').classList.contains('open')) return;
  if (e.key === 'ArrowRight') lightboxNav(1);
  if (e.key === 'ArrowLeft')  lightboxNav(-1);
  if (e.key === 'Escape')     closeLightbox();
});
</script>
@endsection
