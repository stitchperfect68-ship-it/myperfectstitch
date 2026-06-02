@extends('layouts.app')
@section('title', 'About — My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/context.css') }}"/>
@endpush

@section('content')
<div class="page-hero">
  <div class="page-hero-label">Our Story</div>
  <h1>About My Perfect <em>Stitch</em></h1>
</div>

<section class="ctx-section">
  <div class="ctx-inner">
    <div class="ctx-grid">
      <div class="ctx-img-frame reveal">
        <img src="https://i.ibb.co/YFDJ9zPQ/bb-29.jpg" alt="Ruth Kayira Mooto — Founder" loading="lazy"/>
      </div>
      <div class="ctx-body reveal">
        <p>My Perfect Stitch is a <strong>Zambian design and manufacturing business</strong> operating across three verticals — Bags, Furniture, and Interior Spaces — transforming ideas into thoughtfully crafted products and environments.</p>
        <p>From custom-branded bags for leading corporates to bespoke furniture collections and commercial interior fit-outs, we deliver work that is <strong>culturally grounded, functional, and built to perform</strong>.</p>
        <p>Founded by <strong>Ruth Kayira Mooto</strong>, My Perfect Stitch operates on a single conviction: African businesses must deliver world-class products <em>consistently</em>, not occasionally — backed by documented process, disciplined QC, and 8+ years of institutional delivery.</p>
        <blockquote>"Quality is not an outcome of chance, but a product of process."</blockquote>
        <div class="ctx-pillars">
          <div class="ctx-pillar"><h4><i class="fas fa-industry"></i> In-House Production</h4><p>Full manufacturing control in Lusaka — minimising lead times and keeping quality internal.</p></div>
          <div class="ctx-pillar"><h4><i class="fas fa-pen-ruler"></i> Design-Led</h4><p>Every brief approached from a design perspective first, ensuring products communicate brand clarity.</p></div>
          <div class="ctx-pillar"><h4><i class="fas fa-globe-africa"></i> Regional Capability</h4><p>Positioned for SADC and COMESA engagement, with systems built for regional export.</p></div>
          <div class="ctx-pillar"><h4><i class="fas fa-check-circle"></i> Documented QC</h4><p>A quality control process that is repeatable, auditable, and built for large-scale contracts.</p></div>
        </div>
        <div style="margin-top:28px"><button class="btn-quote-section" onclick="openQuoteModal()">Request a Quote →</button></div>
      </div>
    </div>

    @if($clients->count())
    <div class="ctx-clients reveal" style="margin-top:60px">
      <h3>Trusted by Zambia's Leading Organizations</h3>
      <div class="ctx-client-grid">
        @foreach($clients as $client)
          <div class="ctx-client-tag">{{ $client->name }}</div>
        @endforeach
      </div>
    </div>
    @endif

    @if($team->count())
    <div style="margin-top:60px">
      <div class="sec-label reveal">The Team</div>
      <h2 style="font-family:var(--font-display);font-size:clamp(1.6rem,3vw,2.2rem);font-weight:700;color:#100736;margin-bottom:8px" class="reveal">Meet the People Behind the <em style="color:#F9B040;font-style:italic">Stitch</em></h2>
      <div style="width:60px;height:3px;background:#F9B040;margin-bottom:36px" class="reveal"></div>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px" class="reveal">
        @foreach($team as $member)
          <div style="text-align:center">
            <img style="width:100%;aspect-ratio:1;border-radius:6px;object-fit:cover;border:3px solid #e8e4f0;margin-bottom:12px" src="{{ $member->photo ? \Illuminate\Support\Facades\Storage::disk('public')->url($member->photo) : 'https://i.ibb.co/YFDJ9zPQ/bb-29.jpg' }}" alt="{{ $member->name }}" loading="lazy"/>
            <div style="font-weight:700;font-size:.95rem;color:#100736;margin-bottom:4px">{{ $member->name }}</div>
            <div style="font-size:.78rem;color:#888;text-transform:uppercase;letter-spacing:.08em">{{ $member->role }}</div>
          </div>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
