@extends('layouts.app')
@section('title', 'Portfolio — My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portfolio.css') }}"/>
@endpush

@section('content')

<div class="pf-hero">
  <div class="reveal">
    <div class="sec-label">Portfolio</div>
    <h1>Work We're <em>Proud Of</em></h1>
    <p>A look at some of the organisations we've partnered with to bring their brand identity to life — one stitch at a time.</p>
  </div>
</div>

{{-- FILTER --}}
<div class="pf-filter">
  <div class="pf-filter-row">
    <button class="pf-filter-btn active" data-cat="all" onclick="filterCategory('all', this)">All Projects</button>
    <button class="pf-filter-btn" data-cat="bags" onclick="filterCategory('bags', this)">Bags</button>
    <button class="pf-filter-btn" data-cat="interior" onclick="filterCategory('interior', this)">Interior Design</button>
    <button class="pf-filter-btn" data-cat="furniture" onclick="filterCategory('furniture', this)">Furniture</button>
  </div>
  <div class="pf-filter-row" id="subFilterBags" style="display:none;">
    <button class="pf-sub-btn active" onclick="filterClient('all', this)">All Bags</button>
    <button class="pf-sub-btn" onclick="filterClient('stanbic', this)">Stanbic Bank</button>
    <button class="pf-sub-btn" onclick="filterClient('mof', this)">LuSE</button>
    <button class="pf-sub-btn" onclick="filterClient('hobbiton', this)">Hobbiton</button>
    <button class="pf-sub-btn" onclick="filterClient('airtel', this)">Airtel</button>
  </div>
  <div class="pf-filter-row" id="subFilterInterior" style="display:none;">
    <button class="pf-sub-btn active" onclick="filterClient('all', this)">All Interior</button>
    <button class="pf-sub-btn" onclick="filterClient('bongohive', this)">Bongohive</button>
    <button class="pf-sub-btn" onclick="filterClient('hybrid', this)">Hybrid</button>
    <button class="pf-sub-btn" onclick="filterClient('mosi-absa', this)">Mosi-oa-Tunya ABSA</button>
  </div>
  <div class="pf-filter-row" id="subFilterFurniture" style="display:none;">
    <button class="pf-sub-btn active" onclick="filterClient('all', this)">All Furniture</button>
    <button class="pf-sub-btn" onclick="filterClient('furn-absa', this)">ABSA</button>
    <button class="pf-sub-btn" onclick="filterClient('latitude15', this)">Latitude15</button>
    <button class="pf-sub-btn" onclick="filterClient('furn-bongohive', this)">Bongohive</button>
    <button class="pf-sub-btn" onclick="filterClient('furn-hybrid', this)">Hybrid</button>
  </div>
</div>

{{-- ── STANBIC ────────────────────────────────────────────────────────────────── --}}
<section class="pf-project" id="stanbic" data-category="bags" data-client="stanbic">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Stanbic Bank · Anakazi Banking</div>
        <h2 style="margin-top:10px;">Embroidered Laptop Bags<br/>for <em>Anakazi Banking</em></h2>
      </div>
    </div>
    <div class="pf-layout layout-wide">
      <div class="pf-gallery g-2 reveal">
        <img src="{{ asset('assets/stanbic1.jpg') }}" alt="Stanbic embroidered laptop bag"/>
      </div>
      <div class="pf-text reveal">
        <p>Embroidered laptop bags for Anakazi Banking.</p>
        <p>Embroidery remains one of the strongest branding methods for logos that are bold, simple, and built to stand out.</p>
        <p>The result? A professional finish that carries your identity everywhere your team goes.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Get a Quote</a>
      </div>
    </div>
  </div>
</section>

{{-- ── LuSE / MOF ─────────────────────────────────────────────────────────────── --}}
<section class="pf-project" id="mof" data-category="bags" data-client="mof">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Financial Services · Corporate Branding</div>
        <h2 style="margin-top:10px;">Lusaka Securities<br/><em>Exchange (LuSE)</em></h2>
      </div>
    </div>
    <div class="pf-layout">
      <div class="pf-text reveal">
        <p>The Lusaka Securities Exchange has elevated their corporate identity with custom branded bags from My Perfect Stitch — purpose-built for a professional team at the heart of Zambia's capital markets.</p>
        <p>Get in touch today to learn how My Perfect Stitch can help your organisation tell its story through high-quality, branded bags.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Start Your Order</a>
      </div>
      <div class="pf-gallery g-main-plus reveal">
        <img src="{{ asset('assets/mof1.jpg') }}" alt="LuSE branded bag"/>
        <img src="{{ asset('assets/mof2.jpg') }}" alt="LuSE branded bag detail"/>
      </div>
    </div>
  </div>
</section>

{{-- ── HOBBITON ───────────────────────────────────────────────────────────────── --}}
<section class="pf-project" id="hobbiton" data-category="bags" data-client="hobbiton">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Tech Company · Team Identity</div>
        <h2 style="margin-top:10px;">Branded Laptop Sleeves<br/>for <em>Hobbiton</em></h2>
      </div>
    </div>
    <div class="pf-layout layout-wide">
      <div class="pf-gallery g-2 reveal">
        <img src="{{ asset('assets/hob.jpg') }}" alt="Hobbiton laptop sleeve"/>
        <img src="{{ asset('assets/hob1.jpg') }}" alt="Hobbiton laptop sleeve detail"/>
      </div>
      <div class="pf-text reveal">
        <p>Hobbiton's software development team is now equipped with branded laptop sleeves designed to reflect both their tech culture and brand identity.</p>
        <p>As companies increasingly recognize the importance of personalized, functional accessories, we're here to provide solutions that enhance both team identity and professionalism.</p>
        <p>As you plan for the year ahead, consider how your team carries your brand every day. Let's work together to design customized, high-quality bags that align with your 2026 goals.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Plan for 2026</a>
      </div>
    </div>
  </div>
</section>

{{-- ── AIRTEL ─────────────────────────────────────────────────────────────────── --}}
<section class="pf-project" id="airtel" data-category="bags" data-client="airtel">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Telecoms · Dual-Option Branding</div>
        <h2 style="margin-top:10px;">Laptop Sleeves for<br/><em>Airtel</em></h2>
      </div>
    </div>
    <div class="pf-layout">
      <div class="pf-text reveal">
        <p>For Airtel, we delivered two thoughtfully designed options — each serving a different work style.</p>
        <ul>
          <li>The <strong>new-design laptop sleeve with handles</strong> — for professionals who need structure, durability, and easy carry on the move.</li>
          <li>The <strong>standard laptop sleeve</strong> — for those who prefer a lighter, sleek, and minimal everyday option.</li>
        </ul>
        <p>Same identity. Different needs. One brand experience.</p>
        <p>We are currently taking orders for both options, customised to your organisation's branding and requirements.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Order Now</a>
      </div>
      <div class="pf-gallery g-2x2 reveal">
        <img src="{{ asset('assets/airtel.jpg') }}" alt="Airtel branded laptop sleeve"/>
        <img src="{{ asset('assets/airtel1.jpg') }}" alt="Airtel branded laptop sleeve with handles"/>
        <img src="{{ asset('assets/airtel2.jpg') }}" alt="Airtel standard laptop sleeve"/>
        <img src="{{ asset('assets/airtel3.jpg') }}" alt="Airtel laptop sleeve detail"/>
      </div>
    </div>
  </div>
</section>

{{-- ── BONGOHIVE INTERIOR ─────────────────────────────────────────────────────── --}}
<section class="pf-project" id="bongohive" data-category="interior" data-client="bongohive">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Innovation Hub · Interior Design</div>
        <h2 style="margin-top:10px;">Interior Design for<br/><em>Bongohive</em></h2>
      </div>
    </div>
    <div class="pf-layout layout-wide">
      <div class="pf-gallery g-2x2 reveal">
        <img src="{{ asset('assets/portfolio/bongohive/b1 (1).jpg') }}" alt="Bongohive interior design"/>
        <img src="{{ asset('assets/portfolio/bongohive/b1 (2).jpg') }}" alt="Bongohive interior detail"/>
        <img src="{{ asset('assets/portfolio/bongohive/b1 (3).jpg') }}" alt="Bongohive workspace design"/>
        <img src="{{ asset('assets/portfolio/bongohive/b1 (4).jpg') }}" alt="Bongohive upholstery work"/>
      </div>
      <div class="pf-text reveal">
        <p>Bongohive is one of Lusaka's most vibrant coworking and innovation hubs. We partnered with their team to design and execute an interior that reflects their collaborative, creative, and entrepreneurial culture.</p>
        <p>The space needed to feel open, inspiring, and professional — an environment where ideas grow. Every detail, from upholstery to soft furnishings, was carefully considered to bring that vision to life.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Get a Quote</a>
      </div>
    </div>
  </div>
</section>

{{-- ── HYBRID INTERIOR ────────────────────────────────────────────────────────── --}}
<section class="pf-project" id="hybrid" data-category="interior" data-client="hybrid">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Corporate · Interior Design</div>
        <h2 style="margin-top:10px;">Interior Design for<br/><em>Hybrid</em></h2>
      </div>
    </div>
    <div class="pf-layout">
      <div class="pf-text reveal">
        <p>For Hybrid, we delivered a tailored interior design project that blends functionality with a strong brand presence.</p>
        <p>The brief called for a workspace that feels modern and purposeful — one that communicates the company's identity to both team members and visiting clients. We matched every material and finish to their brand palette for a cohesive result.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Start Your Project</a>
      </div>
      <div class="pf-gallery g-2x2 reveal">
        <img src="{{ asset('assets/portfolio/hybrid/h1 (1).jpg') }}" alt="Hybrid interior design"/>
        <img src="{{ asset('assets/portfolio/hybrid/h1 (3).jpg') }}" alt="Hybrid workspace detail"/>
        <img src="{{ asset('assets/portfolio/hybrid/h1 (4).jpg') }}" alt="Hybrid office upholstery"/>
        <img src="{{ asset('assets/portfolio/hybrid/h1 (5).jpg') }}" alt="Hybrid interior finish"/>
      </div>
    </div>
  </div>
</section>

{{-- ── MOSI-OA-TUNYA ABSA INTERIOR ───────────────────────────────────────────── --}}
<section class="pf-project" id="mosi-absa" data-category="interior" data-client="mosi-absa">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Financial Services · Interior Design</div>
        <h2 style="margin-top:10px;">Mosi-oa-Tunya Innovation Hub<br/>for <em>ABSA</em></h2>
      </div>
    </div>
    <div class="pf-layout layout-wide">
      <div class="pf-gallery g-main-plus reveal">
        <img class="pf-img-main" src="{{ asset('assets/portfolio/mosi-oa-tunya-innovation ABSA/mosi1 (1).jpg') }}" alt="ABSA Mosi-oa-Tunya innovation hub interior"/>
        <img src="{{ asset('assets/portfolio/mosi-oa-tunya-innovation ABSA/mosi1 (2).jpg') }}" alt="ABSA innovation hub detail"/>
        <img src="{{ asset('assets/portfolio/mosi-oa-tunya-innovation ABSA/mosi1 (3).jpg') }}" alt="ABSA innovation hub upholstery"/>
      </div>
      <div class="pf-text reveal">
        <p>ABSA's Mosi-oa-Tunya Innovation space is a flagship initiative designed to foster creativity and collaboration within the financial sector.</p>
        <p>We were privileged to contribute to the interior, creating an environment that bridges the professionalism of a leading African bank with the open, innovative spirit the space needed to embody.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Work With Us</a>
      </div>
    </div>
    <div class="pf-gallery g-3 pf-gallery-full reveal">
      @for ($i = 1; $i <= 9; $i++)
        <img src="{{ asset('assets/portfolio/mosi-oa-tunya-innovation ABSA/mos ('.$i.').jpg') }}" alt="ABSA innovation hub — view {{ $i }}"/>
      @endfor
    </div>
  </div>
</section>

{{-- ── ABSA FURNITURE ─────────────────────────────────────────────────────────── --}}
<section class="pf-project" id="furn-absa" data-category="furniture" data-client="furn-absa">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Financial Services · Custom Furniture</div>
        <h2 style="margin-top:10px;">Custom Furniture for<br/><em>ABSA</em></h2>
      </div>
    </div>
    <div class="pf-layout layout-wide">
      <div class="pf-gallery g-main-plus reveal">
        <img class="pf-img-main" src="{{ asset('assets/portfolio/furniture/absa (1).jpg') }}" alt="ABSA custom furniture"/>
        <img src="{{ asset('assets/portfolio/furniture/absa (2).jpg') }}" alt="ABSA furniture detail"/>
        <img src="{{ asset('assets/portfolio/furniture/absa (3).jpg') }}" alt="ABSA furniture finish"/>
      </div>
      <div class="pf-text reveal">
        <p>For ABSA, we designed and delivered bespoke furniture that meets the high standards of a leading African financial institution — combining durability, aesthetics, and brand alignment.</p>
        <p>Every piece was crafted to complement the professional environment while reflecting ABSA's identity through material choice and finish.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Get a Quote</a>
      </div>
    </div>
    <div class="pf-gallery g-2 pf-gallery-full reveal">
      <img src="{{ asset('assets/portfolio/furniture/absa (4).jpg') }}" alt="ABSA furniture — view 4"/>
      <img src="{{ asset('assets/portfolio/furniture/absa (5).jpg') }}" alt="ABSA furniture — view 5"/>
    </div>
  </div>
</section>

{{-- ── LATITUDE15 FURNITURE ───────────────────────────────────────────────────── --}}
<section class="pf-project" id="latitude15" data-category="furniture" data-client="latitude15">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Tech &amp; Innovation · Custom Furniture</div>
        <h2 style="margin-top:10px;">Custom Furniture for<br/><em>Latitude15</em></h2>
      </div>
    </div>
    <div class="pf-layout">
      <div class="pf-text reveal">
        <p>Latitude15 needed furniture that matched the premium and modern feel of their workspace. We delivered custom pieces built to their exact specifications — functional, refined, and built to last.</p>
        <p>The result is a work environment that reflects the quality and professionalism Latitude15 is known for.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Start Your Project</a>
      </div>
      <div class="pf-gallery g-2 reveal">
        <img src="{{ asset('assets/portfolio/furniture/latitude15 (1).jpg') }}" alt="Latitude15 custom furniture"/>
        <img src="{{ asset('assets/portfolio/furniture/latitude15 (2).jpg') }}" alt="Latitude15 furniture detail"/>
      </div>
    </div>
  </div>
</section>

{{-- ── BONGOHIVE FURNITURE ────────────────────────────────────────────────────── --}}
<section class="pf-project" id="furn-bongohive" data-category="furniture" data-client="furn-bongohive">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Innovation Hub · Custom Furniture</div>
        <h2 style="margin-top:10px;">Custom Furniture for<br/><em>Bongohive</em></h2>
      </div>
    </div>
    <div class="pf-layout layout-wide">
      <div class="pf-gallery g-main-plus reveal">
        <img class="pf-img-main" src="{{ asset('assets/portfolio/furniture/bongohive (1).jpg') }}" alt="Bongohive custom furniture"/>
        <img src="{{ asset('assets/portfolio/furniture/bongohive (2).jpg') }}" alt="Bongohive furniture detail"/>
        <img src="{{ asset('assets/portfolio/furniture/bongohive (3).jpg') }}" alt="Bongohive furniture finish"/>
      </div>
      <div class="pf-text reveal">
        <p>Bongohive's collaborative spirit demanded furniture that is as dynamic as the people using it. We crafted custom pieces that support both focused work and open collaboration — built to move with the space.</p>
        <p>Durable, thoughtfully designed, and perfectly aligned with the hub's creative identity.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Get a Quote</a>
      </div>
    </div>
  </div>
</section>

{{-- ── HYBRID FURNITURE ───────────────────────────────────────────────────────── --}}
<section class="pf-project" id="furn-hybrid" data-category="furniture" data-client="furn-hybrid">
  <div class="pf-project-inner">
    <div class="pf-project-header reveal">
      <div style="width:100%">
        <div class="pf-client-badge">Corporate · Custom Furniture</div>
        <h2 style="margin-top:10px;">Custom Furniture for<br/><em>Hybrid</em></h2>
      </div>
    </div>
    <div class="pf-layout">
      <div class="pf-text reveal">
        <p>For Hybrid, we produced custom furniture that elevates the office environment — designed to reflect the company's modern, professional brand while providing comfort and longevity.</p>
        <p>Each piece was tailored to fit the specific dimensions and aesthetic of their workspace.</p>
        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="pf-cta">Start Your Project</a>
      </div>
      <div class="pf-gallery g-2 reveal">
        <img src="{{ asset('assets/portfolio/furniture/hybrid (1).jpg') }}" alt="Hybrid custom furniture"/>
        <img src="{{ asset('assets/portfolio/furniture/hybrid (2).jpg') }}" alt="Hybrid furniture detail"/>
      </div>
    </div>
  </div>
</section>

{{-- STATS STRIP --}}
<div class="pf-stats-strip">
  <div><div class="pf-stat-num">200+</div><div class="pf-stat-lbl">Corporate Clients</div></div>
  <div><div class="pf-stat-num">20,000+</div><div class="pf-stat-lbl">Bags Crafted</div></div>
  <div><div class="pf-stat-num">100%</div><div class="pf-stat-lbl">Made in Zambia</div></div>
  <div><div class="pf-stat-num">15+</div><div class="pf-stat-lbl">Industries Served</div></div>
</div>

{{-- CTA BANNER --}}
<div class="pf-cta-banner">
  <p>From concept to completion, we design and deliver solutions that fit your needs.</p>
  <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank" class="btn-white">WhatsApp Us Now</a>
</div>

@endsection

@section('scripts')
<script>
const revObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
}, {threshold: 0.08});
document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

function filterCategory(cat, btn) {
  document.querySelectorAll('.pf-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('subFilterBags').style.display      = cat === 'bags'      ? 'flex' : 'none';
  document.getElementById('subFilterInterior').style.display  = cat === 'interior'  ? 'flex' : 'none';
  document.getElementById('subFilterFurniture').style.display = cat === 'furniture' ? 'flex' : 'none';
  document.querySelectorAll('.pf-sub-btn').forEach(b => b.classList.remove('active'));
  if (cat === 'bags')      document.querySelector('#subFilterBags .pf-sub-btn').classList.add('active');
  if (cat === 'interior')  document.querySelector('#subFilterInterior .pf-sub-btn').classList.add('active');
  if (cat === 'furniture') document.querySelector('#subFilterFurniture .pf-sub-btn').classList.add('active');
  applyProjectFilter(cat, 'all');
}
function filterClient(client, btn) {
  document.querySelectorAll('.pf-sub-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  const activeCat = document.querySelector('.pf-filter-btn.active').dataset.cat;
  applyProjectFilter(activeCat, client);
}
function applyProjectFilter(cat, client) {
  document.querySelectorAll('.pf-project').forEach(section => {
    let show = true;
    if (cat !== 'all' && section.dataset.category !== cat) show = false;
    if (client !== 'all' && section.dataset.client !== client) show = false;
    section.style.display = show ? '' : 'none';
  });
}
if (window.location.hash) {
  setTimeout(() => {
    const el = document.querySelector(window.location.hash);
    if (el) el.scrollIntoView({behavior: 'smooth'});
  }, 600);
}
</script>
@endsection
