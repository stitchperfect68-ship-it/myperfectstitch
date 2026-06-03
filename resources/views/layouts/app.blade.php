<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>@yield('title', 'My Perfect Stitch — Creating Happiness, Lusaka Zambia')</title>
<link rel="icon" type="image/jpeg" href="{{ asset('assets/extracted/img_01.jpg') }}"/>
<meta name="description" content="@yield('meta_description', 'Zambia\'s premier design and manufacturing studio — custom bags, bespoke furniture, and commercial interiors. Built in Lusaka, delivered to world-class standards.')"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="@yield('title', 'My Perfect Stitch')"/>
<meta property="og:image" content="{{ asset('assets/extracted/img_01.jpg') }}"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous"/>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet"/></noscript>
@yield('preload')
<link rel="stylesheet" href="{{ asset('css/mps.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/profile-panel.css') }}"/>
@stack('styles')
@yield('head_extra')
<script>
  window.SUPABASE_URL     = '{{ config("supabase.url") }}';
  window.SUPABASE_ANON_KEY = '{{ config("supabase.anon_key") }}';
  window.SUPABASE_SYNC_URL = '{{ route("auth.supabase.sync") }}';
  window.SUPABASE_SIGNOUT_URL = '{{ route("auth.supabase.signout") }}';
  window.CSRF_TOKEN = '{{ csrf_token() }}';
</script>
</head>
<body>

<div class="page-loader" id="pageLoader">
  <div class="loader-brand">My Perfect <span>Stitch</span></div>
  <div class="loader-thread">
    <svg viewBox="0 0 180 60" xmlns="http://www.w3.org/2000/svg">
      <path class="thread-path-back" d="M0 30 Q45 10 90 30 Q135 50 180 30"/>
      <path class="thread-path" d="M0 30 Q45 10 90 30 Q135 50 180 30"/>
      <g class="needle" transform="translate(-18,0)">
        <rect x="0" y="27" width="18" height="3" rx="1.5" fill="#fff"/>
        <ellipse cx="2.5" cy="28.5" rx="1.5" ry="1" fill="#140f37"/>
        <line x1="0" y1="28.5" x2="-8" y2="28.5" stroke="#F9B040" stroke-width="1.5" stroke-dasharray="3 2"/>
      </g>
    </svg>
  </div>
  <div class="loader-dots"><span></span><span></span><span></span></div>
  <div class="loader-stripe"></div>
</div>

<nav id="mainNav">
  <div class="nav-inner">
    <a href="{{ route('home') }}" class="nav-logo">
      <img src="{{ asset('assets/extracted/img_01.jpg') }}" alt="My Perfect Stitch Logo" style="width:48px;height:48px;border-radius:50%;object-fit:cover;"/>
      <div class="nav-logo-text"><strong>My Perfect Stitch</strong><span>Creating Happiness</span></div>
    </a>
    <ul class="nav-links">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('context') }}">About</a></li>
      <li><a href="{{ route('home') }}#shop">Shop</a></li>
      <li><a href="{{ route('portfolio') }}">Portfolio</a></li>
      <li><a href="{{ route('events') }}">Events</a></li>
      <li><a href="{{ route('gallery') }}">Gallery</a></li>
      <li><a href="{{ route('home') }}#contact">Contact</a></li>
      <li>
        <a href="{{ route('cart.index') }}" class="cart-nav-btn">
          <i class="fas fa-shopping-bag"></i>
          @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
          @if($cartCount > 0)<span class="cart-badge">{{ $cartCount }}</span>@endif
        </a>
      </li>
    </ul>
    <div style="display:flex;align-items:center;gap:12px">
      <button class="nav-search-btn" onclick="openSearch()"><i class="fas fa-search"></i></button>
      <div class="nav-auth-wrap">
        <button class="nav-auth-btn" id="navAuthBtn" onclick="toggleNavAuthMenu()"><i class="fas fa-user"></i> Sign In</button>
        <div class="nav-auth-dropdown" id="navAuthDropdown" style="display:none">
          <a href="#" class="nav-auth-dd-item" onclick="openProfilePanel();return false"><i class="fas fa-user-circle"></i> My Profile</a>
          <a href="#" class="nav-auth-dd-item" onclick="openProfilePanel('orders');return false"><i class="fas fa-box"></i> My Orders</a>
          <button class="nav-auth-dd-item" onclick="sbSignOut()"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
        </div>
      </div>
      <button class="nav-quote-btn" onclick="openQuoteModal()">Get a Quote</button>
      <button class="hamburger" id="hamburger" onclick="toggleMenu()"><span></span><span></span><span></span></button>
    </div>
  </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
  <a href="{{ route('home') }}" onclick="closeMenu()">Home</a>
  <a href="{{ route('context') }}" onclick="closeMenu()">About</a>
  <a href="{{ route('home') }}#shop" onclick="closeMenu()">Shop</a>
  <a href="{{ route('portfolio') }}" onclick="closeMenu()">Portfolio</a>
  <a href="{{ route('events') }}" onclick="closeMenu()">Events</a>
  <a href="{{ route('gallery') }}" onclick="closeMenu()">Gallery</a>
  <a href="{{ route('home') }}#contact" onclick="closeMenu()">Contact</a>
  <a href="{{ route('cart.index') }}" onclick="closeMenu()">Cart{{ $cartCount > 0 ? ' ('.$cartCount.')' : '' }}</a>
  <div class="mobile-menu-divider"></div>
  {{-- Auth section — updated by _updateNavAuth() when Supabase session changes --}}
  <div id="mobileAuthSection">
    <button class="mobile-signin-btn" onclick="closeMenu();openAuthModal()">
      <i class="fas fa-user"></i> Sign In
    </button>
  </div>
  <button class="mobile-quote-btn" onclick="closeMenu();openQuoteModal()">Get a Quote</button>
</div>

<div class="search-overlay" id="searchOverlay">
  <button class="search-close" onclick="closeSearch()">×</button>
  <div class="search-input-wrap">
    <i class="fas fa-search"></i>
    <input type="text" id="searchInput" placeholder="Search products, pages…" oninput="runSearch(this.value)" autocomplete="off"/>
  </div>
  <div class="search-results" id="searchResults"></div>
</div>

<div id="quoteModal" class="qmodal-overlay" onclick="handleOverlayClick(event)">
  <div class="qmodal-box">
    <button class="qmodal-close" onclick="closeQuoteModal()" aria-label="Close">&times;</button>
    <div class="qmodal-progress">
      <div class="qmodal-progress-bar" id="quoteProgressBar"></div>
    </div>
    <form id="quoteForm" onsubmit="return false;">

      {{-- Step 1: Service type --}}
      <div class="qstep" id="qstep-1">
        <div class="qstep-header">
          <span class="qstep-num">01 / 05</span>
          <h2 class="qstep-title">What service are you looking for?</h2>
          <p class="qstep-desc">Select the option that best describes your order.</p>
        </div>
        <div class="qchoices">
          <label class="qchoice"><input type="radio" name="service" value="Branded Corporate Bags"/> <span>Branded Corporate Bags</span></label>
          <label class="qchoice"><input type="radio" name="service" value="Conference &amp; Event Bags"/> <span>Conference &amp; Event Bags</span></label>
          <label class="qchoice"><input type="radio" name="service" value="Retail / Individual Bags"/> <span>Retail / Individual Bags</span></label>
          <label class="qchoice"><input type="radio" name="service" value="Custom Bag Design"/> <span>Custom Bag Design</span></label>
          <label class="qchoice"><input type="radio" name="service" value="Custom Furniture"/> <span>Custom Furniture</span></label>
          <label class="qchoice"><input type="radio" name="service" value="Furniture Reupholstery &amp; Repairs"/> <span>Furniture Reupholstery &amp; Repairs</span></label>
          <label class="qchoice"><input type="radio" name="service" value="Interior Design &amp; Fit-Outs"/> <span>Interior Design &amp; Fit-Outs</span></label>
          <label class="qchoice"><input type="radio" name="service" value="Other / General Enquiry"/> <span>Other / General Enquiry</span></label>
        </div>
        <p class="qerr" id="err-1"></p>
        <div class="qnav"><button type="button" class="qbtn-next" onclick="qNext(1)">Next &rarr;</button></div>
      </div>

      {{-- Step 2: Quantity + Budget --}}
      <div class="qstep" id="qstep-2" style="display:none;">
        <div class="qstep-header">
          <span class="qstep-num">02 / 05</span>
          <h2 class="qstep-title">How many pieces do you need?</h2>
          <p class="qstep-desc">Give us an approximate quantity.</p>
        </div>
        <div class="qfield">
          <label for="qQty">Number of pieces</label>
          <input type="number" id="qQty" name="quantity" min="1" placeholder="e.g. 10"/>
        </div>
        <div class="qfield">
          <label for="qBudget">Approximate budget (ZMW)</label>
          <input type="text" id="qBudget" name="budget" placeholder="e.g. 5000"/>
        </div>
        <p class="qerr" id="err-2"></p>
        <div class="qnav">
          <button type="button" class="qbtn-back" onclick="qBack(2)">&larr; Back</button>
          <button type="button" class="qbtn-next" onclick="qNext(2)">Next &rarr;</button>
        </div>
      </div>

      {{-- Step 3: Description --}}
      <div class="qstep" id="qstep-3" style="display:none;">
        <div class="qstep-header">
          <span class="qstep-num">03 / 05</span>
          <h2 class="qstep-title">Describe what you want</h2>
          <p class="qstep-desc">Include fabric preferences, colours, style details, or any inspiration.</p>
        </div>
        <div class="qfield">
          <label for="qDesc">Description</label>
          <textarea id="qDesc" name="description" rows="5" placeholder="e.g. Navy blue corporate bags with gold embroidery logo on the front pocket…"></textarea>
        </div>
        <p class="qerr" id="err-3"></p>
        <div class="qnav">
          <button type="button" class="qbtn-back" onclick="qBack(3)">&larr; Back</button>
          <button type="button" class="qbtn-next" onclick="qNext(3)">Next &rarr;</button>
        </div>
      </div>

      {{-- Step 4: Deadline + Contact --}}
      <div class="qstep" id="qstep-4" style="display:none;">
        <div class="qstep-header">
          <span class="qstep-num">04 / 05</span>
          <h2 class="qstep-title">When do you need it?</h2>
          <p class="qstep-desc">Tell us your deadline and how to reach you.</p>
        </div>
        <div class="qfield">
          <label for="qDeadline">Required by (date)</label>
          <input type="date" id="qDeadline" name="deadline"/>
        </div>
        <div class="qfield">
          <label for="qName">Your name</label>
          <input type="text" id="qName" name="name" placeholder="Full name"/>
        </div>
        <div class="qfield">
          <label for="qPhone">Your phone / WhatsApp number</label>
          <input type="tel" id="qPhone" name="phone" placeholder="e.g. 0968 000 000"/>
        </div>
        <p class="qerr" id="err-4"></p>
        <div class="qnav">
          <button type="button" class="qbtn-back" onclick="qBack(4)">&larr; Back</button>
          <button type="button" class="qbtn-next" onclick="qNext(4)">Next &rarr;</button>
        </div>
      </div>

      {{-- Step 5: Review + Send --}}
      <div class="qstep" id="qstep-5" style="display:none;">
        <div class="qstep-header">
          <span class="qstep-num">05 / 05</span>
          <h2 class="qstep-title">Review your quote request</h2>
          <p class="qstep-desc">Check the details below then send via WhatsApp.</p>
        </div>
        <div class="qreview" id="qReviewBox"></div>
        <p class="qerr" id="err-5"></p>
        <div class="qnav">
          <button type="button" class="qbtn-back" onclick="qBack(5)">&larr; Back</button>
          <button type="button" class="qbtn-send" onclick="sendQuoteToWhatsApp()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="vertical-align:middle;margin-right:6px"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            Send via WhatsApp
          </button>
        </div>
      </div>

    </form>
  </div>
</div>

@if(session('success'))
  <div style="position:fixed;bottom:24px;right:24px;z-index:5000;background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;padding:14px 20px;border-radius:6px;max-width:340px;font-size:.9rem;box-shadow:0 4px 20px rgba(0,0,0,.15)" data-flash>
    <i class="fas fa-check-circle" style="margin-right:8px"></i>{{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div style="position:fixed;bottom:24px;right:24px;z-index:5000;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:14px 20px;border-radius:6px;max-width:340px;font-size:.9rem;box-shadow:0 4px 20px rgba(0,0,0,.15)" data-flash>
    <i class="fas fa-exclamation-circle" style="margin-right:8px"></i>{{ session('error') }}
  </div>
@endif

{{-- ── Supabase Auth Modal ──────────────────────────────────────────────────── --}}
<div id="sbAuthOverlay" class="sb-auth-overlay" onclick="if(event.target===this)closeSbAuth()">
  <div class="sb-auth-box">
    <button class="sb-auth-close" onclick="closeSbAuth()" aria-label="Close">&times;</button>
    <div class="sb-auth-brand">
      <img src="{{ asset('assets/extracted/img_01.jpg') }}" alt="My Perfect Stitch Logo" style="width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0;"/>
      <div>
        <div class="sb-auth-title">My Perfect Stitch</div>
        <div class="sb-auth-sub">Sign in to continue</div>
      </div>
    </div>

    {{-- Tabs --}}
    <div class="sb-tabs">
      <button class="sb-tab active" id="tabSignIn" onclick="switchSbTab('signin')">Sign In</button>
      <button class="sb-tab" id="tabSignUp" onclick="switchSbTab('signup')">Create Account</button>
    </div>

    {{-- Sign In --}}
    <div id="sbPanelSignin">
      {{-- Google OAuth --}}
      <button class="sb-btn-google" onclick="sbDoGoogleAuth()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
        Continue with Google
      </button>
      <div class="sb-divider"><span>or sign in with email</span></div>
      <div class="sb-field"><label>Email</label><input type="email" id="sbSignInEmail" placeholder="you@example.com" autocomplete="email"/></div>
      <div class="sb-field"><label>Password</label><input type="password" id="sbSignInPassword" placeholder="Your password" autocomplete="current-password"/></div>
      <p class="sb-err" id="sbSignInErr"></p>
      <button class="sb-btn-primary" id="sbSignInBtn" onclick="sbDoSignIn()">Sign In</button>
      <button class="sb-btn-link" onclick="switchSbTab('reset')">Forgot password?</button>
    </div>

    {{-- Sign Up --}}
    <div id="sbPanelSignup" style="display:none">
      {{-- Google OAuth --}}
      <button class="sb-btn-google" onclick="sbDoGoogleAuth()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
        Continue with Google
      </button>
      <div class="sb-divider"><span>or sign up with email</span></div>
      <div class="sb-field"><label>Full Name</label><input type="text" id="sbSignUpName" placeholder="Your full name" autocomplete="name"/></div>
      <div class="sb-field"><label>Email</label><input type="email" id="sbSignUpEmail" placeholder="you@example.com" autocomplete="email"/></div>
      <div class="sb-field"><label>Password</label><input type="password" id="sbSignUpPassword" placeholder="At least 6 characters" autocomplete="new-password"/></div>
      <p class="sb-err" id="sbSignUpErr"></p>
      <button class="sb-btn-primary" id="sbSignUpBtn" onclick="sbDoSignUp()">Create Account</button>
    </div>

    {{-- Password Reset --}}
    <div id="sbPanelReset" style="display:none">
      <p style="font-size:.88rem;color:#555;margin-bottom:16px;">Enter your email and we'll send you a reset link.</p>
      <div class="sb-field"><label>Email</label><input type="email" id="sbResetEmail" placeholder="you@example.com"/></div>
      <p class="sb-err" id="sbResetErr"></p>
      <p class="sb-ok"  id="sbResetOk"></p>
      <button class="sb-btn-primary" onclick="sbDoReset()">Send Reset Link</button>
      <button class="sb-btn-link" onclick="switchSbTab('signin')">&larr; Back to Sign In</button>
    </div>

  </div>
</div>

<main>@yield('content')</main>

<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
        <img src="{{ asset('assets/extracted/img_14.jpg') }}" alt="My Perfect Stitch Logo" style="width:40px;height:40px;border-radius:50%;object-fit:cover;"/>
        <span style="color:white;font-family:var(--font-display);font-size:1rem;font-weight:700;">My Perfect Stitch</span>
      </div>
      <p>A design led manufacturing institution based in Lusaka, Zambia. We build products and spaces of exceptional quality delivered on time, every time. Serving Zambia, SADC, and COMESA for over 8 years.</p>
    </div>
    <div class="footer-col">
      <h4>Quick Links</h4>
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('context') }}">About Us</a>
      <a href="{{ route('home') }}#shop">Shop</a>
      <a href="{{ route('portfolio') }}">Portfolio</a>
      <a href="{{ route('events') }}">Events</a>
      <a href="{{ route('home') }}#corporate">Services</a>
      <a href="{{ route('home') }}#contact">Contact</a>
    </div>
    <div class="footer-col">
      <h4>Reach Us</h4>
      <a href="tel:+{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}">+{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}</a>
      <a href="mailto:{{ \App\Models\Setting::get('contact_email','info@myperfectstitch.co.zm') }}">{{ \App\Models\Setting::get('contact_email','info@myperfectstitch.co.zm') }}</a>
      <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number','260968531630') }}" target="_blank">WhatsApp Us</a>
      <a href="https://instagram.com/" target="_blank">Instagram</a>
      <a href="https://linkedin.com/" target="_blank">LinkedIn</a>
      <a href="https://facebook.com/" target="_blank">Facebook</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© {{ date('Y') }} My Perfect Stitch. All rights reserved. Headquartered in Lusaka, Zambia.</p>
    <p class="footer-tagline">Quality is not an outcome of chance, but a product of process.</p>
  </div>
</footer>

<script>
window.addEventListener('load',()=>setTimeout(()=>document.getElementById('pageLoader')?.classList.add('hidden'),400));
function toggleMenu(){const h=document.getElementById('hamburger'),m=document.getElementById('mobileMenu');h.classList.toggle('open');m.classList.toggle('open');}
function closeMenu(){document.getElementById('hamburger')?.classList.remove('open');document.getElementById('mobileMenu')?.classList.remove('open');}
function toggleNavAuthMenu(){if(!_sbSession?.user){openAuthModal();return;}const dd=document.getElementById('navAuthDropdown');if(dd)dd.style.display=dd.style.display==='none'?'block':'none';}
document.addEventListener('click',e=>{const wrap=document.querySelector('.nav-auth-wrap');if(wrap&&!wrap.contains(e.target)){const dd=document.getElementById('navAuthDropdown');if(dd)dd.style.display='none';}});
document.addEventListener('click',e=>{const m=document.getElementById('mobileMenu'),h=document.getElementById('hamburger');if(m?.classList.contains('open')&&!m.contains(e.target)&&!h?.contains(e.target))closeMenu();});
const searchIndex=[
  {title:'Home',sub:'Main page',url:'{{ route("home") }}',icon:'fa-home'},
  {title:'Gallery',sub:'Photo gallery',url:'{{ route("gallery") }}',icon:'fa-images'},
  {title:'Portfolio',sub:'Client projects',url:'{{ route("portfolio") }}',icon:'fa-folder-open'},
  {title:'Events',sub:'Events & news',url:'{{ route("events") }}',icon:'fa-calendar'},
  {title:'About Us',sub:'Our story',url:'{{ route("context") }}',icon:'fa-info-circle'},
  {title:'Shop — Bags',sub:'Custom bags',url:'{{ route("home") }}#shop',icon:'fa-shopping-bag'},
  {title:'Shop — Furniture',sub:'Bespoke furniture',url:'{{ route("home") }}#shop',icon:'fa-couch'},
  {title:'Get a Quote',sub:'Request a custom order',url:'#',icon:'fa-envelope',action:'openQuoteModal'},
  {title:'Cart',sub:'Your shopping cart',url:'{{ route("cart.index") }}',icon:'fa-shopping-cart'},
];
function openSearch(){document.getElementById('searchOverlay').classList.add('open');setTimeout(()=>document.getElementById('searchInput').focus(),100);}
function closeSearch(){document.getElementById('searchOverlay').classList.remove('open');}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeSearch();});
function runSearch(q){const box=document.getElementById('searchResults');if(!q.trim()){box.innerHTML='';return;}const r=searchIndex.filter(i=>(i.title+i.sub).toLowerCase().includes(q.toLowerCase()));box.innerHTML=r.length?r.map(i=>`<a class="search-result-item" href="${i.url}"${i.action?` onclick="${i.action}();closeSearch();return false"`:''} ><span class="search-result-icon"><i class="fas ${i.icon}"></i></span><span><span class="search-result-title">${i.title}</span><span class="search-result-sub">${i.sub}</span></span></a>`).join(''):`<div class="search-empty">No results for "<strong>${q}</strong>"</div>`;}
const WHATSAPP='{{ \App\Models\Setting::get("whatsapp_number","260968531630") }}';
const TOTAL_STEPS = 5;
function openQuoteModal() { document.getElementById('quoteModal').classList.add('active'); document.body.style.overflow = 'hidden'; showQStep(1); }
function closeQuoteModal() { document.getElementById('quoteModal').classList.remove('active'); document.body.style.overflow = ''; }
function handleOverlayClick(e) { if (e.target === document.getElementById('quoteModal')) closeQuoteModal(); }
function showQStep(n) {
  for (let i = 1; i <= TOTAL_STEPS; i++) { const el = document.getElementById('qstep-' + i); if (el) el.style.display = (i === n) ? 'block' : 'none'; }
  const pct = ((n - 1) / (TOTAL_STEPS - 1)) * 100;
  document.getElementById('quoteProgressBar').style.width = pct + '%';
}
function qNext(step) {
  const err = document.getElementById('err-' + step); if (err) err.textContent = '';
  if (step === 1) { const sel = document.querySelector('input[name="service"]:checked'); if (!sel) { err.textContent = 'Please select a service to continue.'; return; } }
  if (step === 2) { const qty = document.getElementById('qQty').value.trim(); if (!qty || isNaN(qty) || Number(qty) < 1) { err.textContent = 'Please enter a valid quantity.'; return; } }
  if (step === 3) { const desc = document.getElementById('qDesc').value.trim(); if (desc.length < 10) { err.textContent = 'Please describe your order in a bit more detail.'; return; } }
  if (step === 4) { const nm = document.getElementById('qName').value.trim(); const ph = document.getElementById('qPhone').value.trim(); if (!nm) { err.textContent = 'Please enter your name.'; return; } if (!ph) { err.textContent = 'Please enter your phone / WhatsApp number.'; return; } buildReview(); }
  showQStep(step + 1);
}
function qBack(step) { showQStep(step - 1); }
function buildReview() {
  const sv = (document.querySelector('input[name="service"]:checked') || {}).value || '---';
  const qt = document.getElementById('qQty').value || '---';
  const bg = document.getElementById('qBudget').value || 'Not specified';
  const dc = document.getElementById('qDesc').value || '---';
  const dl = document.getElementById('qDeadline').value || 'Not specified';
  const nm = document.getElementById('qName').value || '---';
  const ph = document.getElementById('qPhone').value || '---';
  document.getElementById('qReviewBox').innerHTML = '<strong>Service:</strong> ' + sv + '<br><strong>Quantity:</strong> ' + qt + ' pieces<br><strong>Budget:</strong> ZMW ' + bg + '<br><strong>Deadline:</strong> ' + dl + '<br><strong>Description:</strong> ' + qesc(dc) + '<br><strong>Name:</strong> ' + qesc(nm) + '<br><strong>Phone:</strong> ' + qesc(ph);
}
function qesc(s) { return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }
function sendQuoteToWhatsApp() {
  const sv = (document.querySelector('input[name="service"]:checked') || {}).value || '---';
  const qt = document.getElementById('qQty').value || '---';
  const bg = document.getElementById('qBudget').value || 'Not specified';
  const dc = document.getElementById('qDesc').value || '---';
  const dl = document.getElementById('qDeadline').value || 'Not specified';
  const nm = document.getElementById('qName').value || '---';
  const ph = document.getElementById('qPhone').value || '---';
  const msg = 'QUOTE REQUEST - My Perfect Stitch\n\nService: ' + sv + '\nQuantity: ' + qt + ' pieces\nBudget: ZMW ' + bg + '\nDeadline: ' + dl + '\n\nDescription:\n' + dc + '\n\nName: ' + nm + '\nPhone: ' + ph;
  // Save to database
  fetch('{{ route("quote.store") }}', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
    body: JSON.stringify({ service_type: sv, quantity: qt !== '---' ? qt : null, budget: bg, description: dc, deadline: dl, name: nm, phone: ph })
  }).catch(() => {});
  // Send to WhatsApp
  window.open('https://wa.me/' + WHATSAPP + '?text=' + encodeURIComponent(msg), '_blank');
  closeQuoteModal();
}
const revealObserver=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('visible');revealObserver.unobserve(e.target);}});},{threshold:0.1});
document.querySelectorAll('.reveal').forEach(el=>revealObserver.observe(el));
window.addEventListener('scroll',()=>{document.getElementById('mainNav').style.boxShadow=window.scrollY>10?'0 4px 20px rgba(0,0,0,.3)':'none';});
setTimeout(()=>document.querySelectorAll('[data-flash]').forEach(el=>el.remove()),4000);
</script>

{{-- Supabase JS Client --}}
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2/dist/umd/supabase.min.js"></script>
<script>
// ── Supabase Auth ─────────────────────────────────────────────────────────────
let _sbClient = null, _sbSession = null, _sbPendingAction = null;

function _initSupabase() {
  if (!window.SUPABASE_URL || window.SUPABASE_URL.includes('your-project-ref')) return;
  try {
    _sbClient = window.supabase.createClient(window.SUPABASE_URL, window.SUPABASE_ANON_KEY);
    _sbClient.auth.onAuthStateChange(async (event, session) => {
      _sbSession = session;
      _updateNavAuth(session);
      if (session && (event === 'SIGNED_IN' || event === 'TOKEN_REFRESHED')) {
        await _syncWithLaravel(session.access_token);
      }
      if (event === 'SIGNED_OUT') {
        await fetch(window.SUPABASE_SIGNOUT_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN } }).catch(() => {});
      }
    });
    _sbClient.auth.getSession().then(async ({ data: { session } }) => {
      _sbSession = session;
      _updateNavAuth(session);
      if (session) await _syncWithLaravel(session.access_token);
      window._sbReady = true;
      document.dispatchEvent(new Event('sb:ready'));
    });
  } catch(e) { console.warn('Supabase init failed:', e); }
}

async function _syncWithLaravel(token) {
  try {
    const r = await fetch(window.SUPABASE_SYNC_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'X-Supabase-Token': token }
    });
    const data = await r.json();
    if (data.success && _sbPendingAction) { const fn = _sbPendingAction; _sbPendingAction = null; fn(); }
  } catch(e) {}
}

function _updateNavAuth(session) {
  const btn = document.getElementById('navAuthBtn');
  const mobileSection = document.getElementById('mobileAuthSection');

  if (session?.user) {
    const name = session.user.user_metadata?.full_name || session.user.email.split('@')[0];

    // Desktop nav button
    if (btn) {
      btn.innerHTML = `<i class="fas fa-user-circle"></i> ${name} <span class="nav-auth-arrow">&#9660;</span>`;
      btn.classList.add('sb-logged-in');
    }
    const dd = document.getElementById('navAuthDropdown');
    if (dd) dd.style.display = '';

    // Mobile menu auth section
    if (mobileSection) {
      mobileSection.innerHTML = `
        <div class="mobile-user-info">
          <i class="fas fa-user-circle"></i>
          <span>${name}</span>
        </div>
        <a href="#" onclick="closeMenu();openProfilePanel('orders')" class="mobile-auth-link">
          <i class="fas fa-box"></i> My Orders
        </a>
        <a href="#" onclick="closeMenu();openProfilePanel('settings')" class="mobile-auth-link">
          <i class="fas fa-user-cog"></i> Profile Settings
        </a>
        <button class="mobile-auth-link mobile-signout-btn" onclick="closeMenu();sbSignOut()">
          <i class="fas fa-sign-out-alt"></i> Sign Out
        </button>`;
    }
  } else {
    // Desktop nav button
    if (btn) {
      btn.innerHTML = '<i class="fas fa-user"></i> Sign In';
      btn.classList.remove('sb-logged-in');
    }
    const dd = document.getElementById('navAuthDropdown');
    if (dd) dd.style.display = 'none';

    // Mobile menu auth section
    if (mobileSection) {
      mobileSection.innerHTML = `
        <button class="mobile-signin-btn" onclick="closeMenu();openAuthModal()">
          <i class="fas fa-user"></i> Sign In
        </button>`;
    }
  }
}

// ── Auth Modal Controls ───────────────────────────────────────────────────────
function openAuthModal(pendingAction) {
  if (pendingAction) _sbPendingAction = pendingAction;
  document.getElementById('sbAuthOverlay').classList.add('active');
  document.body.style.overflow = 'hidden';
  switchSbTab('signin');
}
function closeSbAuth() {
  document.getElementById('sbAuthOverlay').classList.remove('active');
  document.body.style.overflow = '';
}
function switchSbTab(tab) {
  document.getElementById('sbPanelSignin').style.display = tab === 'signin' ? '' : 'none';
  document.getElementById('sbPanelSignup').style.display = tab === 'signup' ? '' : 'none';
  document.getElementById('sbPanelReset').style.display  = tab === 'reset'  ? '' : 'none';
  document.getElementById('tabSignIn').classList.toggle('active', tab === 'signin');
  document.getElementById('tabSignUp').classList.toggle('active', tab === 'signup');
  document.querySelectorAll('.sb-err,.sb-ok').forEach(el => el.textContent = '');
}

// ── Auth Actions ──────────────────────────────────────────────────────────────
async function sbDoGoogleAuth() {
  if (!_sbClient) { alert('Auth not configured. Please check Supabase credentials.'); return; }
  const { error } = await _sbClient.auth.signInWithOAuth({
    provider: 'google',
    options: { redirectTo: window.location.origin + '/auth/callback' },
  });
  if (error) { console.error('Google auth error:', error.message); }
}

async function sbDoSignIn() {
  if (!_sbClient) { alert('Auth not configured. Please check Supabase credentials.'); return; }
  const btn = document.getElementById('sbSignInBtn');
  const email = document.getElementById('sbSignInEmail').value.trim();
  const pw    = document.getElementById('sbSignInPassword').value;
  const err   = document.getElementById('sbSignInErr');
  if (!email || !pw) { err.textContent = 'Email and password are required.'; return; }
  btn.disabled = true; btn.textContent = 'Signing in…';
  const { error } = await _sbClient.auth.signInWithPassword({ email, password: pw });
  btn.disabled = false; btn.textContent = 'Sign In';
  if (error) { err.textContent = error.message; return; }
  closeSbAuth();
}

async function sbDoSignUp() {
  if (!_sbClient) { alert('Auth not configured. Please check Supabase credentials.'); return; }
  const btn  = document.getElementById('sbSignUpBtn');
  const name  = document.getElementById('sbSignUpName').value.trim();
  const email = document.getElementById('sbSignUpEmail').value.trim();
  const pw    = document.getElementById('sbSignUpPassword').value;
  const err   = document.getElementById('sbSignUpErr');
  if (!name || !email || !pw) { err.textContent = 'All fields are required.'; return; }
  if (pw.length < 6) { err.textContent = 'Password must be at least 6 characters.'; return; }
  btn.disabled = true; btn.textContent = 'Creating account…';
  const { error } = await _sbClient.auth.signUp({ email, password: pw, options: { data: { full_name: name } } });
  btn.disabled = false; btn.textContent = 'Create Account';
  if (error) { err.textContent = error.message; return; }
  err.textContent = '';
  document.getElementById('sbSignUpErr').textContent = '';
  // Show confirmation message
  const panel = document.getElementById('sbPanelSignup');
  panel.innerHTML = '<p style="color:#065f46;background:#d1fae5;padding:16px;border-radius:6px;font-size:.9rem"><strong>Account created!</strong> Check your email to confirm, then sign in.</p>';
}

async function sbDoReset() {
  if (!_sbClient) return;
  const email = document.getElementById('sbResetEmail').value.trim();
  const err = document.getElementById('sbResetErr'), ok = document.getElementById('sbResetOk');
  if (!email) { err.textContent = 'Please enter your email.'; return; }
  const { error } = await _sbClient.auth.resetPasswordForEmail(email, { redirectTo: window.location.origin + '/auth/login' });
  if (error) { err.textContent = error.message; } else { ok.textContent = 'Reset link sent — check your email.'; err.textContent = ''; }
}

async function sbSignOut() {
  if (_sbClient) await _sbClient.auth.signOut();
}

// ── Guard helpers (used by quote modal and cart/checkout buttons) ───────────
const _sbConfigured = window.SUPABASE_URL && !window.SUPABASE_URL.includes('your-project-ref');

function requireAuth(action) {
  // If Supabase not yet configured, let through (backend also bypasses)
  if (!_sbConfigured) { action(); return; }
  if (_sbSession?.user) { action(); return; }
  openAuthModal(action);
}

// Checkout helper — call from any "Proceed to Checkout" button
function goToCheckout() {
  requireAuth(() => { window.location.href = '{{ route("checkout.index") }}'; });
}

// ── Patch openQuoteModal to require auth ──────────────────────────────────────
const _origOpenQuoteModal = window.openQuoteModal;
window.openQuoteModal = function() {
  requireAuth(() => _origOpenQuoteModal());
};

// Patch sendQuoteToWhatsApp to include Supabase token
const _origSendQuote = window.sendQuoteToWhatsApp;
window.sendQuoteToWhatsApp = function() {
  const token = _sbSession?.access_token || '';
  // Temporarily patch fetch header
  const origFetch = window.fetch;
  window.fetch = function(url, opts) {
    if (url === '{{ route("quote.store") }}' && token) {
      opts = opts || {};
      opts.headers = { ...(opts.headers || {}), 'X-Supabase-Token': token };
    }
    return origFetch.call(this, url, opts);
  };
  _origSendQuote();
  window.fetch = origFetch;
};

// Patch cart "Add to Cart" and checkout links
document.addEventListener('click', e => {
  const btn = e.target.closest('[data-auth-action="checkout"]');
  if (btn) {
    e.preventDefault();
    requireAuth(() => { window.location.href = '{{ route("checkout.index") }}'; });
  }
});

_initSupabase();
</script>

{{-- ── User Profile Panel ────────────────────────────────────────────────── --}}
<div class="pp-overlay" id="ppOverlay" onclick="if(event.target===this)closeProfilePanel()">
  <div class="pp-drawer" id="ppDrawer">
    <button class="pp-close" onclick="closeProfilePanel()">×</button>

    {{-- Header --}}
    <div class="pp-head">
      <div class="pp-avatar" id="ppAvatar">?</div>
      <div class="pp-name" id="ppName">Loading…</div>
      <div class="pp-email" id="ppEmail"></div>
      <div class="pp-since" id="ppSince"></div>
    </div>

    {{-- Stats bar --}}
    <div class="pp-stats">
      <div class="pp-stat"><div class="pp-stat-val" id="ppStatOrders">—</div><div class="pp-stat-label">Orders</div></div>
      <div class="pp-stat"><div class="pp-stat-val" id="ppStatActive">—</div><div class="pp-stat-label">Active</div></div>
      <div class="pp-stat"><div class="pp-stat-val" id="ppStatSpent">—</div><div class="pp-stat-label">Spent (K)</div></div>
    </div>

    {{-- Tabs --}}
    <div class="pp-tabs">
      <button class="pp-tab active" data-tab="overview" onclick="ppSwitchTab('overview')">Overview</button>
      <button class="pp-tab" data-tab="orders" onclick="ppSwitchTab('orders')">Orders</button>
      <button class="pp-tab" data-tab="settings" onclick="ppSwitchTab('settings')">Settings</button>
    </div>

    {{-- Body --}}
    <div class="pp-body">
      <div id="pp-loader" class="pp-loader"><i class="fas fa-circle-notch"></i><span>Loading your profile…</span></div>

      {{-- Overview --}}
      <div class="pp-panel" id="pp-overview">
        <p class="pp-section-title">Recent Orders</p>
        <div id="ppRecentOrders"></div>
        <div style="text-align:center;margin-top:16px">
          <a href="#" onclick="ppSwitchTab('orders');return false" style="font-size:.8rem;color:#F9B040;font-weight:700">View all orders →</a>
        </div>
      </div>

      {{-- Orders --}}
      <div class="pp-panel" id="pp-orders">
        <p class="pp-section-title">All Orders</p>
        <div id="ppAllOrders"></div>
      </div>

      {{-- Settings --}}
      <div class="pp-panel" id="pp-settings">
        <p class="pp-section-title">Profile Settings</p>
        <form id="ppProfileForm" onsubmit="ppSaveProfile(event)">
          <div class="pp-field">
            <label>Full Name</label>
            <input type="text" id="ppFieldName" name="name" required/>
          </div>
          <div class="pp-field">
            <label>Phone / WhatsApp</label>
            <input type="tel" id="ppFieldPhone" name="phone"/>
          </div>
          <div class="pp-field">
            <label>Email</label>
            <input type="email" id="ppFieldEmail" disabled/>
          </div>
          <button type="submit" class="pp-save-btn" id="ppSaveBtn">Save Changes</button>
          <div class="pp-msg" id="ppMsg"></div>
        </form>
        <button class="pp-signout" onclick="closeProfilePanel();sbSignOut()">
          <i class="fas fa-sign-out-alt"></i> Sign Out
        </button>
      </div>
    </div>
  </div>
</div>

<script>
const PP_DATA_URL = '{{ route("customer.profile.data") }}';
const PP_UPDATE_URL = '{{ route("customer.profile.update.ajax") }}';
let _ppData = null;

function openProfilePanel(tab) {
  if (!_sbSession?.user) { openAuthModal(); return; }
  document.getElementById('ppOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
  ppLoadData(tab || 'overview');
}
function closeProfilePanel() {
  document.getElementById('ppOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

function ppSwitchTab(tab) {
  document.querySelectorAll('.pp-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
  document.querySelectorAll('.pp-panel').forEach(p => p.classList.toggle('active', p.id === 'pp-' + tab));
}

async function ppLoadData(tab) {
  document.getElementById('pp-loader').style.display = 'block';
  document.querySelectorAll('.pp-panel').forEach(p => p.classList.remove('active'));
  ppSwitchTab(tab || 'overview');
  try {
    const r = await fetch(PP_DATA_URL, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
    if (!r.ok) throw new Error('not ok');
    _ppData = await r.json();
    ppRender(_ppData);
  } catch(e) {
    document.getElementById('pp-loader').innerHTML = '<p style="color:#c0392b;font-size:.85rem">Could not load profile. Please try again.</p>';
  }
}

function ppRender(d) {
  document.getElementById('pp-loader').style.display = 'none';

  // Header
  document.getElementById('ppAvatar').textContent = d.customer.initials || '?';
  document.getElementById('ppName').textContent   = d.customer.name;
  document.getElementById('ppEmail').textContent  = d.customer.email || '';
  document.getElementById('ppSince').textContent  = 'Member since ' + d.customer.member_since;

  // Stats
  document.getElementById('ppStatOrders').textContent = d.stats.total_orders;
  document.getElementById('ppStatActive').textContent = d.stats.active_orders;
  document.getElementById('ppStatSpent').textContent  = Number(d.stats.total_spent).toLocaleString();

  // Recent orders (overview — last 3)
  const recent = d.orders.slice(0, 3);
  document.getElementById('ppRecentOrders').innerHTML = recent.length
    ? recent.map(ppOrderCard).join('')
    : '<div class="pp-empty"><i class="fas fa-shopping-bag"></i><p>No orders yet.</p></div>';

  // All orders
  document.getElementById('ppAllOrders').innerHTML = d.orders.length
    ? d.orders.map(ppOrderCard).join('')
    : '<div class="pp-empty"><i class="fas fa-shopping-bag"></i><p>No orders yet.</p></div>';

  // Settings form
  document.getElementById('ppFieldName').value  = d.customer.name || '';
  document.getElementById('ppFieldPhone').value = d.customer.phone || '';
  document.getElementById('ppFieldEmail').value = d.customer.email || '';

  // Show active panel
  document.querySelector('.pp-panel.active')?.classList.add('active');
}

function ppOrderCard(o) {
  const colors = { yellow:'pp-badge-yellow', blue:'pp-badge-blue', indigo:'pp-badge-indigo', purple:'pp-badge-purple', orange:'pp-badge-orange', green:'pp-badge-green', red:'pp-badge-red', gray:'pp-badge-gray' };
  const badgeCls = colors[o.status_color] || 'pp-badge-gray';
  return `<div class="pp-order">
    <div class="pp-order-icon"><i class="fas fa-box"></i></div>
    <div class="pp-order-info">
      <div class="pp-order-ref">${o.ref}</div>
      <div class="pp-order-meta">${o.date} · ${o.items_count} item${o.items_count !== 1 ? 's' : ''}</div>
    </div>
    <div class="pp-order-right">
      <div class="pp-order-amount">K${Number(o.total).toLocaleString()}</div>
      <div><span class="pp-badge ${badgeCls}">${o.status_label}</span></div>
    </div>
  </div>`;
}

async function ppSaveProfile(e) {
  e.preventDefault();
  const btn = document.getElementById('ppSaveBtn');
  const msg = document.getElementById('ppMsg');
  btn.disabled = true; btn.textContent = 'Saving…';
  msg.className = 'pp-msg';
  try {
    const r = await fetch(PP_UPDATE_URL, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.CSRF_TOKEN, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify({ name: document.getElementById('ppFieldName').value, phone: document.getElementById('ppFieldPhone').value })
    });
    const data = await r.json();
    if (data.success) {
      msg.textContent = 'Profile updated!'; msg.className = 'pp-msg ok';
      document.getElementById('ppName').textContent = data.name;
      if (_ppData) _ppData.customer.name = data.name;
    } else { throw new Error(); }
  } catch(err) {
    msg.textContent = 'Could not save. Please try again.'; msg.className = 'pp-msg err';
  }
  btn.disabled = false; btn.textContent = 'Save Changes';
}
</script>

@yield('scripts')
</body>
</html>
