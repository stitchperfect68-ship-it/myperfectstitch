@extends('layouts.app')
@section('title', 'My Perfect Stitch — Creating Happiness, Lusaka Zambia')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}"/>
@endpush

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────────────────────── --}}
<section id="home">
  <div class="hero-bg-imgs">
    <div class="hero-bg-img active" style="background-image:url('{{ asset('assets/sliderbags.jpg') }}')"></div>
    <div class="hero-bg-img" data-bg="https://i.ibb.co/Z1VjcMKX/bb-9.jpg"></div>
    <div class="hero-bg-img" data-bg="https://i.ibb.co/24HQDKw/bb-6.jpg"></div>
  </div>
  <div class="hero-overlay"></div>

  <div class="hero-slider-wrap">
    {{-- SLIDE 1 — BAGS --}}
    <div class="hero-slide active">
      <div class="hero-eyebrow">✦ Handcrafted Bags</div>
      <h1>Every Bag<br/><em>Tells a Story</em></h1>
      <p class="hero-sub">Chitenge bags crafted in Lusaka.</p>
      <div class="hero-buttons">
        <a href="#shop" class="btn-red">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 7h-3V6a4 4 0 00-8 0v1H5a1 1 0 00-1 1v11a3 3 0 003 3h10a3 3 0 003-3V8a1 1 0 00-1-1zm-9-1a2 2 0 014 0v1h-4V6zm8 13a1 1 0 01-1 1H7a1 1 0 01-1-1V9h2v1a1 1 0 002 0V9h4v1a1 1 0 002 0V9h2v10z"/></svg>
          Shop Bags
        </a>
        <button class="btn-quote-hero" onclick="openQuoteModal()">Request a Quote</button>
      </div>
    </div>

    {{-- SLIDE 2 — FURNITURE --}}
    <div class="hero-slide">
      <div class="hero-eyebrow">✦ Bespoke Furniture</div>
      <h1>Custom Furniture<br/><em>Built for You</em></h1>
      <p class="hero-sub">Designed &amp; made in Lusaka.</p>
      <div class="hero-buttons">
        <a href="#corporate" class="btn-red">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><polyline points="9 22 9 12 15 12 15 22" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          View Furniture
        </a>
        <button class="btn-quote-hero" onclick="openQuoteModal()">Request a Quote</button>
      </div>
    </div>

    {{-- SLIDE 3 — INTERIORS --}}
    <div class="hero-slide">
      <div class="hero-eyebrow">✦ Interior Spaces</div>
      <h1>Interiors Built<br/><em>to Stand out</em></h1>
      <p class="hero-sub">From concept to fit-out.</p>
      <div class="hero-buttons">
        <a href="#corporate" class="btn-red">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><polyline points="9 22 9 12 15 12 15 22" fill="none" stroke="currentColor" stroke-width="2"/></svg>
          Interior Projects
        </a>
        <a href="#contact" class="btn-outline">Book Consultation</a>
        <button class="btn-quote-hero" onclick="openQuoteModal()">Request a Quote</button>
      </div>
    </div>

    <div class="slider-dots">
      <button class="sdot active" onclick="goSlide(0)"></button>
      <button class="sdot" onclick="goSlide(1)"></button>
      <button class="sdot" onclick="goSlide(2)"></button>
    </div>
    <button class="slider-arrow prev" onclick="moveSlide(-1)">&#8592;</button>
    <button class="slider-arrow next" onclick="moveSlide(1)">&#8594;</button>
  </div>
</section>

{{-- ── CORPORATE / WHAT WE DO ───────────────────────────────────────────────── --}}
<section id="corporate">
  <div class="reveal" style="position:relative;z-index:1;">
    <h2 class="sec-label"></h2>
    <h2>What We Do</h2>
    <div class="divider-stripe"></div>
    <div class="corp-intro"><p>Three verticals, one vision we are a full-spectrum design and manufacturing partner, delivering branded bags, bespoke furniture, and complete interior fit-outs through a disciplined, process-driven system, delivered on time, every time.</p></div>
  </div>

  <div class="corp-cards">
    <div class="corp-card reveal" onclick="window.location.href='{{ route('gallery') }}#bags'" style="cursor:pointer;">
      <div class="corp-num">01</div>
      <div class="corp-card-img">
        <img src="https://i.ibb.co/dsZGfSD8/bb-33.jpg" alt="Bag Manufacturing" loading="lazy"/>
      </div>
      <div class="corp-rule"></div>
      <h3>Bag Manufacturing</h3>
      <p>Custom and branded bags designed for organisations and individuals from concept to delivery. High-volume, export ready soft goods built for institutional orders without loss of detail.</p>
      <div class="service-tags">
        <span class="svc-tag">Backpacks</span>
        <span class="svc-tag">Laptop Bags</span>
        <span class="svc-tag">Tote Bags</span>
        <span class="svc-tag">Travel Bags</span>
      </div>
    </div>
    <div class="corp-card reveal" onclick="window.location.href='{{ route('gallery') }}#furniture'" style="cursor:pointer;">
      <div class="corp-num">02</div>
      <div class="corp-card-img">
        <img src="https://i.ibb.co/842NYtKj/bb-25.jpg" alt="Custom Furniture" loading="lazy"/>
      </div>
      <div class="corp-rule"></div>
      <h3>Custom Furniture</h3>
      <p>Bespoke furniture built around intentional design, combining unique fabrics and cultural expression. Engineered to withstand rigorous institutional and hospitality use.</p>
      <div class="service-tags">
        <span class="svc-tag">Sofas</span>
        <span class="svc-tag">Arm Chairs</span>
        <span class="svc-tag">Benches</span>
        <span class="svc-tag">Upholstery</span>
      </div>
    </div>
    <div class="corp-card reveal" onclick="window.location.href='{{ route('gallery') }}#interior'" style="cursor:pointer;">
      <div class="corp-num">03</div>
      <div class="corp-card-img">
        <img src="https://i.ibb.co/JSXZdC7/bb-12.jpg" alt="Interior Spaces" loading="lazy"/>
      </div>
      <div class="corp-rule"></div>
      <h3>Interior Spaces</h3>
      <p>Spatial solutions from concept to installation balancing form, function, and durability. End-to-end workplace design, hospitality fit-outs, and retail space transformation.</p>
      <div class="service-tags">
        <span class="svc-tag">Fit-outs</span>
        <span class="svc-tag">Workplace Design</span>
        <span class="svc-tag">Hospitality</span>
        <span class="svc-tag">Retail</span>
      </div>
    </div>
  </div>

  <div class="clients-wrap reveal" style="margin-bottom:40px;">
    <div class="clients-lbl">Our Proprietary Four-Stage System</div>
    <div class="clients-row" style="gap:12px;flex-wrap:wrap;">
      <div class="cbadge">① Brief &amp; Feasibility</div>
      <div class="cbadge">② Consultation &amp; Design</div>
      <div class="cbadge">③ Production &amp; QC</div>
      <div class="cbadge">④ Delivery &amp; Execution</div>
    </div>
  </div>

  <div class="clients-wrap reveal">
    <div class="clients-lbl">Trusted by Zambia's Leading Institutions</div>
    <div class="clients-row">
      @foreach($clients->take(9) as $client)
        <div class="cbadge">{{ $client->name }}</div>
      @endforeach
    </div>
    <div style="margin-top:40px;text-align:center;">
      <button class="btn-quote-corp" onclick="openQuoteModal()">Request a Corporate Quote &rarr;</button>
    </div>
  </div>
</section>

{{-- ── ABOUT ─────────────────────────────────────────────────────────────────── --}}
<section id="about">
  <div class="reveal">
    <div class="sec-label">Who We Are</div>
    <h2>Design. Manufacturing. <em>Interiors.</em></h2>
    <div class="divider-stripe"></div>
  </div>
  <div class="about-grid">
    <div class="about-img-frame reveal desktop-only">
      <img src="https://i.ibb.co/YFDJ9zPQ/bb-29.jpg" alt="My Perfect Stitch team at work" loading="lazy"/>
      <div class="about-img-tag">✦ Proudly Zambian</div>
    </div>
    <div class="about-img-frame reveal mobile-only">
      <img src="https://i.ibb.co/WrLrFBq/bb-27.jpg" alt="My Perfect Stitch at a corporate event" loading="lazy"/>
      <div class="about-img-tag">✦ Proudly Zambian</div>
    </div>
    <div class="about-body reveal">
      <p>My Perfect Stitch is a <strong>Zambian design and manufacturing business</strong> operating across three verticals Bags, Furniture, and Interior Spaces transforming ideas into thoughtfully crafted products.</p>
      <p>From custom-branded bags for leading corporates to bespoke furniture collections and commercial interior fit-outs, we deliver work that is <strong>culturally grounded, functional, and built to perform</strong>.</p>
      <p>Founded by <strong>Ruth Kayira Mooto</strong>, we operate on a single conviction: African businesses must deliver world-class products <em>consistently</em>, not occasionally backed by documented process, disciplined QC, and 8+ years of institutional delivery.</p>
      <p style="font-style:italic;border-left:3px solid #F9B040;padding-left:16px;margin-top:24px;color:#F9B040;font-weight:600;">"Quality is not an outcome of chance, but a product of process."</p>
      <div class="about-pillars" style="margin-top:28px;">
        <div class="pillar"><h4><i class="fa-solid fa-industry"></i> In-House Production</h4><p>Full manufacturing control in Lusaka minimising lead times and keeping quality internal.</p></div>
        <div class="pillar"><h4><i class="fa-solid fa-pen-ruler"></i> Design Led Thinking</h4><p>Every brief is approached from a design perspective first, ensuring products communicate brand clarity.</p></div>
        <div class="pillar"><h4><i class="fa-solid fa-globe-africa"></i> Regional Capability</h4><p>Strategically positioned for SADC and COMESA engagement, with systems built for regional export.</p></div>
        <div class="pillar"><h4><i class="fa-solid fa-circle-check"></i> Documented QC</h4><p>Our quality control process is repeatable, auditable, and built for large scale contracts without loss of detail.</p></div>
      </div>
      <div style="margin-top:32px;">
        <button class="btn-quote-section" onclick="openQuoteModal()">Request a Quote &rarr;</button>
      </div>
    </div>
  </div>
</section>

{{-- ── CLIENTS TICKER ────────────────────────────────────────────────────────── --}}
<section class="clients-section" id="clients">
  <div class="overline">Track Record</div>
  <h2 class="section-title">Trusted by <em>Leading</em> Organizations</h2>
  <div class="clients-ticker">
    <div class="clients-track" id="clientsTrack">
      @foreach($clients as $client)<div class="client-pill">{{ $client->name }}</div>@endforeach
      @foreach($clients as $client)<div class="client-pill">{{ $client->name }}</div>@endforeach
    </div>
  </div>
</section>

{{-- ── TEAM ──────────────────────────────────────────────────────────────────── --}}
<section class="team-section" id="team">
  <div class="team-img-full">
    <img src="{{ asset('assets/sitebg3.jpg') }}" alt="My Perfect Stitch team at work" loading="lazy"/>
    <div class="team-caption">
      <div class="team-caption-text">
        <h2>Our Team</h2>
        <p>A multidisciplinary team of designers, makers, and project managers committed to quality and efficient delivery across all verticals.</p>
      </div>
    </div>
  </div>
  <div class="team-values">
    <div class="value-item">
      <h4>Communication</h4>
      <p>Clear, consistent dialogue at every stage of your project.</p>
    </div>
    <div class="value-item">
      <h4>Customer Centric</h4>
      <p>Your vision drives every decision we make.</p>
    </div>
    <div class="value-item">
      <h4>Innovative</h4>
      <p>Combining African heritage with contemporary design thinking.</p>
    </div>
    <div class="value-item">
      <h4>Industry Experts</h4>
      <p>Deep technical knowledge across bags, furniture, and interiors.</p>
    </div>
    <div class="value-item">
      <h4>Leadership</h4>
      <p>Setting the standard for quality manufacturing in Zambia.</p>
    </div>
  </div>
</section>

{{-- ── CRAFT STRIP ───────────────────────────────────────────────────────────── --}}
<div class="chitenge-strip">
  <div class="strip-grid">
    <div class="strip-text reveal">
      <div class="sec-label">Our Process</div>
      <h2>Every Bag,<br/><em>Built with Intent</em></h2>
      <div class="divider-stripe"></div>
      <p>Every product that leaves My Perfect Stitch is the result of a structured, repeatable manufacturing process from design brief to final QC inspection. We don't rely on chance. We rely on systems.</p>
      <p>Our in house Lusaka facility gives us full production control tighter lead times, consistent quality, and the capacity to scale institutional orders without compromising the detail that makes each piece exceptional.</p>
    </div>
    <div class="fabric-mosaic reveal">
      <div class="fabric-tile"><img src="{{ asset('assets/extracted/img_03.jpg') }}" alt="Blue peacock laptop bag" loading="lazy"/></div>
      <div class="fabric-tile"><img src="{{ asset('assets/extracted/img_04.webp') }}" alt="Blue backpack" loading="lazy"/></div>
      <div class="fabric-tile"><img src="{{ asset('assets/extracted/img_05.webp') }}" alt="Teal wave laptop bag" loading="lazy"/></div>
    </div>
  </div>
</div>

{{-- ── STATS BAR ─────────────────────────────────────────────────────────────── --}}
<div class="hero-stats-bar">
  <div class="stat-item"><div class="stat-num" data-target="10" data-suffix="+">0+</div><div class="stat-lbl">Years in Operation</div></div>
  <div class="stat-item"><div class="stat-num" data-target="25" data-suffix="+">0+</div><div class="stat-lbl">Specialist Team</div></div>
  <div class="stat-item"><div class="stat-num" data-target="200" data-suffix="+">0+</div><div class="stat-lbl">Institutional Clients</div></div>
</div>

{{-- ── SHOP ──────────────────────────────────────────────────────────────────── --}}
<section id="shop">
  <div class="shop-head reveal">
    <div>
      <div class="sec-label">The Collection</div>
      <h2>Shop our <em>Pieces</em></h2>
      <div class="divider-stripe"></div>
    </div>
    <div class="shop-filters">
      <div class="filter-row">
        <button class="ftab active" onclick="setShopParent('all',this)">All Products</button>
        <button class="ftab" onclick="setShopParent('bags',this)">Bags</button>
        <button class="ftab" onclick="setShopParent('furniture',this)">Furniture</button>
      </div>
      <div class="sub-dropdown-wrap" id="dropdownBags" style="display:none;">
        <select class="sub-dropdown" id="bagSubSelect" onchange="setShopSub(this.value)">
          <option value="all">All Bags</option>
          @foreach($categories->where('slug','bags')->first()?->children ?? [] as $sub)
            <option value="{{ $sub->slug }}">{{ $sub->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="sub-dropdown-wrap" id="dropdownFurniture" style="display:none;">
        <select class="sub-dropdown" id="furnSubSelect" onchange="setShopSub(this.value)">
          <option value="all">All Furniture</option>
          @foreach($categories->where('slug','furniture')->first()?->children ?? [] as $sub)
            <option value="{{ $sub->slug }}">{{ $sub->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>

  {{-- Embed product data for JS modal --}}
  @php
    $productData = $products->map(function($p) {
        $img = $p->primary_image_path;
        return [
            'id'          => $p->id,
            'name'        => $p->name,
            'price'       => $p->price,
            'formatted'   => $p->price > 0 ? 'K'.number_format($p->price, 0) : 'Price on Request',
            'desc'        => $p->short_description ?? $p->subcategory?->name ?? '',
            'image'       => $img ? (str_starts_with($img,'assets/') ? asset($img) : \Illuminate\Support\Facades\Storage::disk('public')->url($img)) : '',
            'cart_url'    => route('cart.add', $p->id),
        ];
    })->values()->toArray();
  @endphp
  <script>window.PRODUCTS_DATA = @json($productData);</script>

  <div class="products-grid" id="productsGrid">
    @foreach($products as $product)
      @php
        $img = $product->primary_image_path;
        $imgSrc = $img ? (str_starts_with($img,'assets/') ? asset($img) : \Illuminate\Support\Facades\Storage::disk('public')->url($img)) : '';
      @endphp
      <div class="product-card" data-parent="{{ $product->category?->slug ?? '' }}" data-sub="{{ $product->subcategory?->slug ?? '' }}">
        @if($product->tag_label)
          <div class="product-tag {{ $product->tag_type ?? 'tag-std' }}">{{ $product->tag_label }}</div>
        @endif
        <div class="product-img-wrap" style="cursor:pointer" onclick="openProductModal({{ $product->id }})">
          @if($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $product->name }}" loading="lazy"/>
          @endif
          <div class="product-overlay">
            <button class="overlay-btn">Quick View</button>
          </div>
        </div>
        <div class="product-info">
          <h3>{{ $product->name }}</h3>
          <div>{{ $product->short_description ?? $product->subcategory?->name }}</div>
          <div class="product-footer">
            @if($product->price > 0)
              <span class="price">{{ $product->formatted_price }}</span>
              <button class="buy-btn add-to-cart-btn" data-product-id="{{ $product->id }}">
                <i class="fas fa-shopping-bag"></i> Add to Cart
              </button>
            @else
              <span class="price price-req">Price on Request</span>
              <button class="buy-btn" onclick="openQuoteModal()">Get a Quote</button>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div class="load-more-wrap" id="loadMoreWrap">
    <button class="load-more-btn" onclick="loadMoreShop()">Load More</button>
  </div>
</section>

{{-- ── PORTFOLIO PREVIEW ─────────────────────────────────────────────────────── --}}
<section id="portfolio-preview">
  <div class="reveal">
    <div class="sec-label">Our Work</div>
    <h2>Brands We've <em>Worked with</em></h2>
    <p style="max-width:560px;line-height:1.8;opacity:0.75;margin-top:12px;">
      From banking institutions to tech startups see how we've helped Zambia's leading organisations carry their brand with pride.
    </p>
  </div>
  <div class="portfolio-preview-grid reveal">
    <div class="pf-card" onclick="location.href='{{ route('portfolio') }}#stanbic'">
      <img src="{{ asset('assets/stanbic1.jpg') }}" alt="Stanbic Bank branded laptop bags" loading="lazy"/>
      <div class="pf-card-overlay">
        <div class="pf-card-client">Stanbic Bank</div>
        <div class="pf-card-title">Embroidered Laptop Bags for Anakazi Banking</div>
      </div>
    </div>
    <div class="pf-card" onclick="location.href='{{ route('portfolio') }}#mof'">
      <img src="{{ asset('assets/mof1.jpg') }}" alt="Ministry of Fisheries branded bags" loading="lazy"/>
      <div class="pf-card-overlay">
        <div class="pf-card-client">Ministry of Fisheries &amp; Livestock</div>
        <div class="pf-card-title">Custom Laptop Bags &amp; Backpacks</div>
      </div>
    </div>
    <div class="pf-card" onclick="location.href='{{ route('portfolio') }}#hobbiton'">
      <img src="{{ asset('assets/hob.jpg') }}" alt="Hobbiton branded laptop sleeves" loading="lazy"/>
      <div class="pf-card-overlay">
        <div class="pf-card-client">Hobbiton</div>
        <div class="pf-card-title">Branded Laptop Sleeves for a Tech Team</div>
      </div>
    </div>
    <div class="pf-card" onclick="location.href='{{ route('portfolio') }}#airtel'">
      <img src="{{ asset('assets/airtel.jpg') }}" alt="Airtel branded laptop sleeves" loading="lazy"/>
      <div class="pf-card-overlay">
        <div class="pf-card-client">Airtel</div>
        <div class="pf-card-title">Dual-Option Branded Laptop Sleeves</div>
      </div>
    </div>
  </div>
  <div class="portfolio-preview-footer reveal">
    <a href="{{ route('portfolio') }}" class="btn-red">View Full Portfolio</a>
    <button class="btn-quote-section" onclick="openQuoteModal()">Request a Quote &rarr;</button>
  </div>
</section>

{{-- ── FURNITURE MAINTENANCE ──────────────────────────────────────────────────── --}}
<section id="furniture-maintenance">
  <div class="shop-head reveal">
    <div>
      <div class="sec-label">By My Perfect Stitch</div>
      <h2>Furniture <em>Maintenance</em></h2>
      <p style="max-width:520px;color:#555;font-size:0.97rem;line-height:1.8;margin-top:14px;">Breathing new life into existing pieces with expert detailing and structural care.</p>
      <div class="divider-stripe"></div>
    </div>
  </div>
  <div class="fmaint-grid">
    <div class="fmaint-images reveal">
      <div class="fmaint-img-wrap">
        <img src="{{ asset('assets/furniture/before.jpg') }}" alt="Before restoration" loading="lazy"/>
        <div class="fmaint-img-label">Before</div>
      </div>
      <div class="fmaint-img-wrap">
        <img src="{{ asset('assets/furniture/after.jpg') }}" alt="After restoration" loading="lazy"/>
        <div class="fmaint-img-label">After</div>
      </div>
    </div>
    <div class="fmaint-services reveal">
      <div class="fmaint-item">
        <div class="fmaint-icon"><i class="fa-solid fa-couch"></i></div>
        <div>
          <h4>Upholstery Refresh</h4>
          <p>Update fabrics and finishes to give your furniture a clean, renewed look.</p>
        </div>
      </div>
      <div class="fmaint-item">
        <div class="fmaint-icon"><i class="fa-solid fa-arrows-rotate"></i></div>
        <div>
          <h4>Asset Life Extension</h4>
          <p>Reinforce and upgrade key areas to keep your furniture in use for longer.</p>
        </div>
      </div>
      <div class="fmaint-item">
        <div class="fmaint-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
        <div>
          <h4>Restoration &amp; Repairs</h4>
          <p>Fix structural and surface damage to restore your furniture's function and appearance.</p>
        </div>
      </div>
      <div class="fmaint-item">
        <div class="fmaint-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <div>
          <h4>Preventive Maintenance</h4>
          <p>Regular care and minor fixes to keep furniture in good condition and avoid major repairs.</p>
        </div>
      </div>
      <div style="margin-top:32px;">
        <button class="btn-quote-section" onclick="openQuoteModal()">Book a Service &rarr;</button>
      </div>
    </div>
  </div>
</section>

{{-- ── CONTACT ───────────────────────────────────────────────────────────────── --}}
<section id="contact">
  <div class="reveal" style="position:relative;z-index:1;">
    <div class="sec-label">Get in Touch</div>
    <h2>Request a Quote or<br/><em>Book a Consultation</em></h2>
    <div class="divider-stripe"></div>
  </div>
  <div class="contact-grid">
    <div class="contact-left">
      <div class="contact-items">
        <div class="ci"><div class="ci-icon">📍</div><div class="ci-detail"><h4>Location</h4><p>25 Parliament Road, Olympia<br/>Lusaka, Zambia</p></div></div>
        <div class="ci"><div class="ci-icon">📞</div><div class="ci-detail"><h4>Phone / WhatsApp</h4><a href="tel:+{{ $whatsappNumber }}">+{{ $whatsappNumber }}</a></div></div>
        <div class="ci"><div class="ci-icon">✉️</div><div class="ci-detail"><h4>Email</h4><a href="mailto:{{ \App\Models\Setting::get('contact_email','info@myperfectstitch.co.zm') }}">{{ \App\Models\Setting::get('contact_email','info@myperfectstitch.co.zm') }}</a></div></div>
        <div class="ci"><div class="ci-icon">🌐</div><div class="ci-detail"><h4>Website</h4><a href="https://myperfectstitch.co/" target="_blank">myperfectstitch.co</a></div></div>
      </div>
      <div class="cta-box">
        <h4>Let's Talk Business</h4>
        <p>Our team commits to a response within <strong>one business day</strong> for all initial assessments. Serving Zambia, SADC, and COMESA regions we're ready for your next institutional or corporate project.</p>
        <button class="btn-quote-cta" onclick="openQuoteModal()">✦ Request a Quote</button>
      </div>
    </div>
    <div class="contact-right reveal">
      <div class="contact-img-stack">
        <img src="{{ asset('assets/b1 (4).jpg') }}" alt="My Perfect Stitch bags" loading="lazy"/>
        <img src="{{ asset('assets/extracted/img_12.jpg') }}" alt="ZESCO branded bag" loading="lazy"/>
        <img src="{{ asset('assets/extracted/img_13.webp') }}" alt="MultiChoice tote" loading="lazy"/>
      </div>
    </div>
  </div>
</section>

{{-- ── Product Quick-View Modal ─────────────────────────────────────────────── --}}
<div id="productModal" class="pm-overlay" onclick="if(event.target===this)closeProductModal()">
  <div class="pm-box">
    <button class="pm-close" onclick="closeProductModal()" aria-label="Close">&times;</button>
    <div class="pm-inner">
      <div class="pm-img-wrap">
        <img id="pmImg" src="" alt=""/>
      </div>
      <div class="pm-details">
        <div id="pmTag" class="product-tag tag-std" style="display:none;margin-bottom:12px"></div>
        <h2 id="pmName" class="pm-name"></h2>
        <div id="pmPrice" class="pm-price"></div>
        <p id="pmDesc" class="pm-desc"></p>

        <div class="pm-qty-row" id="pmQtyRow">
          <span class="pm-qty-label">Quantity</span>
          <div class="pm-qty-ctrl">
            <button class="pm-qty-btn" onclick="pmAdjustQty(-1)">&#8722;</button>
            <input type="number" id="pmQty" value="1" min="1" class="pm-qty-input" readonly/>
            <button class="pm-qty-btn" onclick="pmAdjustQty(1)">&#43;</button>
          </div>
        </div>

        <div class="pm-actions" id="pmActions">
          <button class="pm-btn-primary" id="pmAddCart" onclick="pmDoAddToCart()">
            <i class="fas fa-shopping-bag"></i> Add to Cart
          </button>
          <button class="pm-btn-checkout" id="pmGoCheckout" onclick="goToCheckout()" style="display:none">
            Proceed to Checkout &rarr;
          </button>
          <button class="pm-btn-quote" id="pmQuoteBtn" style="display:none" onclick="openQuoteModal();closeProductModal()">
            Request a Quote
          </button>
        </div>

        <a href="{{ route('cart.index') }}" class="pm-cart-link" id="pmViewCart" style="display:none">
          <i class="fas fa-shopping-bag"></i> View Cart
        </a>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
// ── Hero Slider ────────────────────────────────────────────────────────────────
let currentSlide = 0;
const slides  = document.querySelectorAll('.hero-slide');
const dots    = document.querySelectorAll('.sdot');
const bgImgs  = document.querySelectorAll('.hero-bg-img');
let autoTimer;

function goSlide(n) {
  slides[currentSlide].classList.remove('active');
  slides[currentSlide].classList.add('exit');
  dots[currentSlide].classList.remove('active');
  bgImgs[currentSlide].classList.remove('active');
  setTimeout(() => { slides[currentSlide].classList.remove('exit'); }, 700);
  currentSlide = (n + slides.length) % slides.length;
  // Lazy-load bg image the first time a slide becomes active
  const bg = bgImgs[currentSlide];
  if (bg.dataset.bg && !bg.style.backgroundImage) {
    bg.style.backgroundImage = "url('" + bg.dataset.bg + "')";
  }
  slides[currentSlide].classList.add('active');
  dots[currentSlide].classList.add('active');
  bgImgs[currentSlide].classList.add('active');
}
function moveSlide(dir) {
  clearInterval(autoTimer);
  goSlide(currentSlide + dir);
  startAuto();
}
function startAuto() {
  autoTimer = setInterval(() => goSlide(currentSlide + 1), 5500);
}
startAuto();

// ── Stats Counter ──────────────────────────────────────────────────────────────
function animateCount(el) {
  const target = +el.dataset.target, suffix = el.dataset.suffix || '', dur = 1800;
  let start = null;
  const step = ts => {
    if (!start) start = ts;
    const p = Math.min((ts - start) / dur, 1);
    el.textContent = Math.floor(p * target) + suffix;
    if (p < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}
const cntObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { animateCount(e.target); cntObs.unobserve(e.target); } });
}, {threshold: 0.5});
document.querySelectorAll('[data-target]').forEach(el => cntObs.observe(el));

// ── Reveal ─────────────────────────────────────────────────────────────────────
const revObs = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revObs.unobserve(e.target); } });
}, {threshold: 0.08});
document.querySelectorAll('.reveal').forEach(el => revObs.observe(el));

// ── Product Filtering + Pagination ────────────────────────────────────────────
let _shopParent = 'all', _shopSub = 'all', _shopPage = 0;
const SHOP_PAGE_SIZE = 6;

function setShopParent(parent, btn) {
  document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('dropdownBags').style.display      = parent === 'bags'      ? 'block' : 'none';
  document.getElementById('dropdownFurniture').style.display = parent === 'furniture' ? 'block' : 'none';
  if (parent === 'bags')      document.getElementById('bagSubSelect').value  = 'all';
  if (parent === 'furniture') document.getElementById('furnSubSelect').value = 'all';
  _shopParent = parent; _shopSub = 'all'; _shopPage = 0;
  renderShop();
}
function setShopSub(val) {
  _shopSub = val; _shopPage = 0;
  renderShop();
}
function loadMoreShop() {
  _shopPage++;
  renderShop();
}
function renderShop() {
  const allCards = Array.from(document.querySelectorAll('.product-card'));
  let filtered;
  if (_shopParent === 'all') {
    const bags = allCards.filter(c => c.dataset.parent === 'bags');
    const furn = allCards.filter(c => c.dataset.parent === 'furniture');
    filtered = [];
    for (let i = 0; i < Math.max(bags.length, furn.length); i++) {
      if (furn[i]) filtered.push(furn[i]);
      if (bags[i]) filtered.push(bags[i]);
    }
  } else {
    filtered = allCards.filter(c => {
      if (c.dataset.parent !== _shopParent) return false;
      if (_shopSub !== 'all' && c.dataset.sub !== _shopSub) return false;
      return true;
    });
  }
  allCards.forEach(c => { c.style.display = 'none'; c.style.order = ''; });
  const showCount = (_shopPage + 1) * SHOP_PAGE_SIZE;
  filtered.slice(0, showCount).forEach((c, idx) => {
    c.style.display = 'block';
    c.style.order = idx;
    c.style.animation = 'popIn 0.35s ease forwards';
  });
  const lmWrap = document.getElementById('loadMoreWrap');
  if (lmWrap) lmWrap.style.display = showCount < filtered.length ? 'flex' : 'none';
}
// Add pop animation and run initial render
const _popStyle = document.createElement('style');
_popStyle.textContent = '@keyframes popIn { from { opacity:0; transform:scale(0.95) translateY(10px); } to { opacity:1; transform:scale(1) translateY(0); } }';
document.head.appendChild(_popStyle);
renderShop();

// ── Product Quick-View Modal ───────────────────────────────────────────────────
let _pmProduct = null;

function openProductModal(productId) {
  const p = (window.PRODUCTS_DATA || []).find(x => x.id === productId);
  if (!p) return;
  _pmProduct = p;
  const hasPrice = p.price > 0;

  document.getElementById('pmImg').src = p.image || '';
  document.getElementById('pmImg').alt = p.name;
  document.getElementById('pmName').textContent = p.name;
  document.getElementById('pmPrice').textContent = p.formatted;
  document.getElementById('pmDesc').textContent = p.desc || '';
  document.getElementById('pmQty').value = 1;

  // Show qty + add-to-cart only for priced items
  document.getElementById('pmQtyRow').style.display  = hasPrice ? 'flex' : 'none';
  document.getElementById('pmAddCart').style.display  = hasPrice ? '' : 'none';
  document.getElementById('pmQuoteBtn').style.display = hasPrice ? 'none' : '';
  document.getElementById('pmGoCheckout').style.display = 'none';
  document.getElementById('pmViewCart').style.display   = 'none';

  // Reset add button
  const addBtn = document.getElementById('pmAddCart');
  addBtn.disabled = false;
  addBtn.innerHTML = '<i class="fas fa-shopping-bag"></i> Add to Cart';

  document.getElementById('productModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeProductModal() {
  document.getElementById('productModal').classList.remove('active');
  document.body.style.overflow = '';
}

function pmAdjustQty(delta) {
  const input = document.getElementById('pmQty');
  input.value = Math.max(1, parseInt(input.value || 1) + delta);
}

async function pmDoAddToCart() {
  if (!_pmProduct) return;
  const qty = parseInt(document.getElementById('pmQty').value || 1);
  const btn = document.getElementById('pmAddCart');
  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding…';

  try {
    const res = await fetch(_pmProduct.cart_url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.CSRF_TOKEN,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ quantity: qty }),
    });

    if (!res.ok) throw new Error('Server error');
    const data = await res.json();

    // Update nav cart badge
    _pmUpdateCartBadge(data.count ?? 0);

    btn.innerHTML = '<i class="fas fa-check"></i> Added!';
    document.getElementById('pmGoCheckout').style.display = '';
    document.getElementById('pmViewCart').style.display   = '';

    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Add More';
    }, 2500);
  } catch (e) {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-shopping-bag"></i> Add to Cart';
    console.error('Add to cart failed:', e);
  }
}

function _pmUpdateCartBadge(count) {
  let badge = document.querySelector('.cart-nav-btn .cart-badge');
  if (count > 0) {
    if (!badge) {
      badge = document.createElement('span');
      badge.className = 'cart-badge';
      document.querySelector('.cart-nav-btn')?.appendChild(badge);
    }
    badge.textContent = count;
    badge.style.display = '';
  } else if (badge) {
    badge.style.display = 'none';
  }
}

// Direct "Add to Cart" from card (no modal)
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
  btn.addEventListener('click', async function(e) {
    e.stopPropagation();
    const productId = parseInt(this.dataset.productId);
    const p = (window.PRODUCTS_DATA || []).find(x => x.id === productId);
    if (!p) return;
    const orig = this.innerHTML;
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    try {
      const res = await fetch(p.cart_url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json' },
        body: JSON.stringify({ quantity: 1 }),
      });
      if (!res.ok) throw new Error();
      const data = await res.json();
      _pmUpdateCartBadge(data.count ?? 0);
      this.innerHTML = '<i class="fas fa-check"></i> Added!';
      setTimeout(() => { this.disabled = false; this.innerHTML = orig; }, 2000);
    } catch {
      this.disabled = false;
      this.innerHTML = orig;
    }
  });
});

// Close modal on Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeProductModal(); });
</script>
@endsection
