@extends('layouts.app')
@section('title', 'Events — My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/events.css') }}"/>
@endpush

@section('content')

<div class="ev-hero">
  <div class="reveal">
    <div class="sec-label">Events &amp; Milestones</div>
    <h1>Stories Worth <em>Sharing</em></h1>
    <p>From awards to workshops — moments that define who we are and how we grow, one stitch at a time.</p>
  </div>
</div>

<div class="ev-filter">
  <button class="ev-filter-btn active" onclick="filterEvents('all', this)">All Events</button>
  <button class="ev-filter-btn" onclick="filterEvents('award', this)">Award</button>
  <button class="ev-filter-btn" onclick="filterEvents('iwd', this)">Women's Day</button>
  <button class="ev-filter-btn" onclick="filterEvents('workshop', this)">Workshop</button>
</div>

{{-- ── AWARD ──────────────────────────────────────────────────────────────────── --}}
<section class="ev-event" id="award" data-event="award">
  <div class="ev-event-inner">
    <div class="reveal">
      <div class="ev-tag">She Entrepreneur Founders Summit 2026</div>
      <h2>Business Excellence <em>Award</em></h2>
    </div>
    <div class="ev-layout">
      <div class="ev-gallery g5 reveal">
        <img class="g-hero" src="{{ asset('assets/award.jpg') }}" alt="Ruth Mooto receiving Business Excellence Award"/>
        <img src="{{ asset('assets/award1.jpg') }}" alt="She Entrepreneur Founders Summit 2026"/>
        <img src="{{ asset('assets/award2.jpg') }}" alt="She Entrepreneur Founders Summit 2026"/>
        <img src="{{ asset('assets/award3.jpg') }}" alt="She Entrepreneur Founders Summit 2026"/>
        <img src="{{ asset('assets/award4.jpg') }}" alt="She Entrepreneur Founders Summit 2026"/>
      </div>
      <div class="ev-text reveal">
        <p>Our Founder &amp; CEO <strong>Ruth Mooto</strong> was a speaker and Business Excellence Award recipient at the <strong>She Entrepreneur Founders Summit 2026</strong> in Lusaka.</p>
        <p>A proud moment for My Perfect Stitch.</p>
      </div>
    </div>
  </div>
</section>

{{-- ── IWD ────────────────────────────────────────────────────────────────────── --}}
<section class="ev-event" id="iwd" data-event="iwd">
  <div class="ev-event-inner">
    <div class="reveal">
      <div class="ev-tag">International Women's Day</div>
      <h2>Celebrating the Women of <em>My Perfect Stitch</em></h2>
    </div>
    <div class="ev-layout text-first">
      <div class="ev-text reveal">
        <p>We celebrate the women of My Perfect Stitch, and women everywhere.</p>
        <p>Women who continue to learn, grow, and support one another every day.</p>
        <p>This year's International Women's Day theme, <strong>"Give to Gain,"</strong> reminds us that when we uplift and support one another, everyone moves forward.</p>
        <p>Happy International Women's Day.</p>
      </div>
      <div class="ev-gallery g7 reveal">
        <img class="g-hero" src="{{ asset('assets/women.jpg') }}" alt="International Women's Day — My Perfect Stitch team"/>
        <img src="{{ asset('assets/women1.jpg') }}" alt="Women of My Perfect Stitch"/>
        <img src="{{ asset('assets/women2.jpg') }}" alt="Women of My Perfect Stitch"/>
        <img src="{{ asset('assets/women3.jpg') }}" alt="Women of My Perfect Stitch"/>
        <img src="{{ asset('assets/women4.jpg') }}" alt="Women of My Perfect Stitch"/>
        <img src="{{ asset('assets/women5.jpg') }}" alt="Women of My Perfect Stitch"/>
        <img src="{{ asset('assets/women6.jpg') }}" alt="Women of My Perfect Stitch"/>
      </div>
    </div>
  </div>
</section>

{{-- ── WORKSHOP ───────────────────────────────────────────────────────────────── --}}
<section class="ev-event" id="workshop" data-event="workshop">
  <div class="ev-event-inner">
    <div class="reveal">
      <div class="ev-tag">Leather Training · Time &amp; Tide Foundation</div>
      <h2>One Week of <em>Hands-On</em> Leather Training</h2>
    </div>
    <div class="ev-layout">
      <div class="ev-gallery g1 reveal">
        <img src="{{ asset('assets/workshop.jpg') }}" alt="Leather training workshop with Fine Stitches Leather"/>
      </div>
      <div class="ev-text reveal">
        <p>We wrapped up a one week, hands-on leather training for three members of our tailoring team, delivered in collaboration with <strong>Fine Stitches Leather</strong>.</p>
        <p>Throughout the week, the team strengthened their leather techniques — from precise cutting and sewing to clean, professional finishing.</p>
        <p>This training, supported through the <strong>Time and Tide Foundation</strong> grant, is part of our continued investment in craftsmanship and capacity building.</p>
        <p>The learning continues — and it shows in the work. Proud of the team for committing to growth.</p>
      </div>
    </div>
  </div>
</section>

{{-- STATS STRIP --}}
<div class="ev-stats">
  <div><div class="ev-stat-num">3+</div><div class="ev-stat-lbl">Events &amp; Milestones</div></div>
  <div><div class="ev-stat-num">1</div><div class="ev-stat-lbl">Award Won</div></div>
  <div><div class="ev-stat-num">100%</div><div class="ev-stat-lbl">Made in Zambia</div></div>
  <div><div class="ev-stat-num">2026</div><div class="ev-stat-lbl">Still Growing</div></div>
</div>

{{-- CTA BANNER --}}
<div class="ev-cta-banner">
  <h2>Be Part of Our Story</h2>
  <p>Partner with My Perfect Stitch for your next event, corporate gifting, or team branding project.</p>
  <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="btn-white">WhatsApp Us Now</a>
</div>

{{-- LIGHTBOX --}}
<div id="lightbox">
  <button id="lightbox-close" onclick="closeLightbox()">&#215;</button>
  <button id="lightbox-prev" onclick="lightboxNav(-1)">&#8592;</button>
  <img id="lightbox-img" src="#" alt=""/>
  <button id="lightbox-next" onclick="lightboxNav(1)">&#8594;</button>
</div>

@endsection

@section('scripts')
<script>
const revObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
}, {threshold: 0.08});
document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

function filterEvents(type, btn) {
  document.querySelectorAll('.ev-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.ev-event').forEach(sec => {
    sec.style.display = (type === 'all' || sec.dataset.event === type) ? '' : 'none';
  });
}

let lbImages = [], lbIndex = 0;
document.querySelectorAll('.ev-gallery img').forEach(img => {
  img.addEventListener('click', () => {
    const gallery = img.closest('.ev-gallery');
    lbImages = Array.from(gallery.querySelectorAll('img')).map(i => i.src);
    lbIndex  = lbImages.indexOf(img.src);
    document.getElementById('lightbox-img').src = lbImages[lbIndex];
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
  });
});
function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
}
function lightboxNav(dir) {
  lbIndex = (lbIndex + dir + lbImages.length) % lbImages.length;
  document.getElementById('lightbox-img').src = lbImages[lbIndex];
}
document.getElementById('lightbox').addEventListener('click', e => {
  if (e.target === document.getElementById('lightbox')) closeLightbox();
});
document.addEventListener('keydown', e => {
  if (!document.getElementById('lightbox').classList.contains('open')) return;
  if (e.key === 'Escape')     closeLightbox();
  if (e.key === 'ArrowRight') lightboxNav(1);
  if (e.key === 'ArrowLeft')  lightboxNav(-1);
});
if (window.location.hash) {
  setTimeout(() => {
    const el = document.querySelector(window.location.hash);
    if (el) el.scrollIntoView({behavior: 'smooth'});
  }, 600);
}
</script>
@endsection
