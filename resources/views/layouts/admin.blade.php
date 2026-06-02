<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>@yield('title','Admin') — MPS Admin</title>
<link rel="icon" type="image/jpeg" href="{{ asset('assets/extracted/img_01.jpg') }}"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous"/>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{height:100%}
body{font-family:'Inter',sans-serif;background:#f0f2f5;color:#1a1a2e;min-height:100vh;font-size:15px;line-height:1.6}
a{text-decoration:none;color:inherit}
::-webkit-scrollbar{width:5px;height:5px}
::-webkit-scrollbar-track{background:#f0f2f5}
::-webkit-scrollbar-thumb{background:#c4c9d4;border-radius:3px}

/* ── SIDEBAR ──────────────────────────────────────────────────────────────── */
.sidebar{
  width:256px;background:#100736;
  display:flex;flex-direction:column;
  position:fixed;top:0;left:0;
  width:256px;height:100vh;height:100dvh;
  overflow-y:auto;overflow-x:hidden;
  z-index:500;
  scrollbar-width:none;
  transition:left .28s cubic-bezier(.4,0,.2,1);
}
.sidebar::-webkit-scrollbar{display:none}
.sidebar-logo{padding:20px 18px;border-bottom:1px solid rgba(255,255,255,.1);flex-shrink:0}
.sidebar-logo a{display:flex;align-items:center;gap:12px;text-decoration:none}
.sidebar-logo-text{color:#fff;font-size:.92rem;font-weight:700;line-height:1.3}
.sidebar-logo-text span{font-size:.72rem;color:rgba(255,255,255,.45);font-weight:400;display:block;margin-top:1px}
.sidebar-section-label{font-size:.68rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:rgba(255,255,255,.3);padding:18px 18px 5px;margin-top:2px}
.sidebar-nav{padding:6px 0;flex:1}
.sidebar-item{
  display:flex;align-items:center;gap:12px;
  padding:11px 18px;
  color:rgba(255,255,255,.7);
  font-size:.9rem;font-weight:500;line-height:1.4;
  cursor:pointer;
  transition:background .15s,color .15s,border-left-color .15s;
  border-left:3px solid transparent;
  min-height:44px;
}
.sidebar-item:hover{background:rgba(255,255,255,.06);color:#fff}
.sidebar-item.active{background:rgba(249,176,64,.12);color:#F9B040;border-left-color:#F9B040;font-weight:600}
.sidebar-item i{width:18px;text-align:center;flex-shrink:0;font-size:.92rem}
.sidebar-footer{flex-shrink:0;padding:14px 18px;border-top:1px solid rgba(255,255,255,.1)}
.sidebar-footer a,.sidebar-footer button{display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.45);font-size:.85rem;line-height:1.5;transition:color .15s;background:none;border:none;cursor:pointer;font-family:inherit;padding:6px 0;width:100%}
.sidebar-footer a:hover,.sidebar-footer button:hover{color:#F9B040}

/* ── MAIN ─────────────────────────────────────────────────────────────────── */
.admin-main{margin-left:256px;display:flex;flex-direction:column;min-height:100vh;min-height:100dvh}
.admin-topbar{
  background:#fff;border-bottom:1px solid #e8eaf0;
  padding:0 24px;height:62px;
  display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:100;
  box-shadow:0 1px 3px rgba(0,0,0,.05);
  flex-shrink:0;
}
.topbar-title{font-size:1.05rem;font-weight:700;color:#100736;line-height:1.3}
.topbar-actions{display:flex;align-items:center;gap:12px}
.topbar-badge{display:flex;align-items:center;gap:7px;background:#f0f2f5;padding:7px 14px;border-radius:20px;font-size:.82rem;font-weight:600;color:#555;line-height:1}
.topbar-badge.admin{background:rgba(16,7,54,.08);color:#100736}
.topbar-badge.staff{background:rgba(249,176,64,.12);color:#d4880a}
.topbar-btn{background:none;border:none;cursor:pointer;color:#888;font-size:1.05rem;padding:8px;transition:color .15s;border-radius:6px;min-height:36px;min-width:36px;display:flex;align-items:center;justify-content:center}
.topbar-btn:hover{color:#100736;background:#f5f5f5}
.admin-content{flex:1;padding:24px 28px}
.page-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;flex-wrap:wrap;gap:14px}
.page-header h1{font-size:1.5rem;font-weight:700;color:#100736;line-height:1.2}
.page-header p{font-size:.9rem;color:#888;margin-top:3px;line-height:1.5}

/* ── CARDS & STATS ────────────────────────────────────────────────────────── */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px}
.stat-card{background:#fff;border-radius:10px;padding:20px 22px;box-shadow:0 1px 4px rgba(0,0,0,.06);border-left:4px solid transparent}
.stat-card.gold{border-left-color:#F9B040}
.stat-card.navy{border-left-color:#100736}
.stat-card.green{border-left-color:#10b981}
.stat-card.red{border-left-color:#ef4444}
.stat-val{font-size:1.9rem;font-weight:800;color:#100736;line-height:1}
.stat-lbl{font-size:.78rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:5px;line-height:1.4}
.stat-icon{float:right;width:42px;height:42px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.05rem;color:#fff;background:#100736}
.stat-icon.gold{background:#F9B040;color:#100736}
.stat-icon.green{background:#10b981}
.stat-icon.red{background:#ef4444}

/* ── TABLE ────────────────────────────────────────────────────────────────── */
.card{background:#fff;border-radius:10px;box-shadow:0 1px 4px rgba(0,0,0,.06);overflow:hidden;margin-bottom:24px}
.card-header{padding:16px 20px;border-bottom:1px solid #f0f2f5;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
.card-header h3{font-size:1rem;font-weight:700;color:#100736;line-height:1.3}
.card-body{padding:20px}
.admin-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
table.admin-table{width:100%;border-collapse:collapse;font-size:.9rem;line-height:1.5}
table.admin-table th{background:#f8f9fb;color:#555;font-size:.76rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;padding:12px 16px;text-align:left;border-bottom:2px solid #e8eaf0;white-space:nowrap}
table.admin-table td{padding:13px 16px;border-bottom:1px solid #f0f2f5;color:#333;vertical-align:middle;line-height:1.5}
table.admin-table tr:last-child td{border-bottom:none}
table.admin-table tr:hover td{background:#fafbfd}
.badge{display:inline-flex;align-items:center;padding:4px 10px;border-radius:12px;font-size:.72rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;line-height:1}
.badge-blue{background:#dbeafe;color:#1d4ed8}
.badge-green{background:#d1fae5;color:#065f46}
.badge-yellow{background:#fef9c3;color:#854d0e}
.badge-red{background:#fee2e2;color:#991b1b}
.badge-gray{background:#f3f4f6;color:#555}
.badge-purple{background:#ede9fe;color:#6d28d9}
.badge-orange{background:#ffedd5;color:#9a3412}

/* ── BUTTONS ──────────────────────────────────────────────────────────────── */
.btn{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:6px;font-family:inherit;font-size:.875rem;font-weight:600;cursor:pointer;border:none;transition:background .15s,transform .1s;line-height:1;min-height:38px}
.btn:hover{transform:translateY(-1px)}
.btn:active{transform:translateY(0)}
.btn-primary{background:#100736;color:#F9B040}
.btn-primary:hover{background:#1a0f4a}
.btn-gold{background:#F9B040;color:#100736}
.btn-gold:hover{background:#fdd085}
.btn-sm{padding:6px 13px;font-size:.8rem;min-height:32px}
.btn-danger{background:#fee2e2;color:#991b1b}
.btn-danger:hover{background:#fecaca}
.btn-outline{background:transparent;border:1.5px solid #e0dce8;color:#555}
.btn-outline:hover{border-color:#100736;color:#100736;background:rgba(16,7,54,.03)}
.btn-success{background:#d1fae5;color:#065f46}
.btn-success:hover{background:#a7f3d0}

/* ── FORMS ────────────────────────────────────────────────────────────────── */
.form-label{display:block;font-size:.85rem;font-weight:600;color:#333;margin-bottom:6px;letter-spacing:.02em;line-height:1.4}
.form-input,.form-select,.form-textarea{width:100%;border:1.5px solid #e0dce8;border-radius:6px;padding:10px 13px;font-family:inherit;font-size:.9rem;color:#1a1a2e;outline:none;transition:border-color .15s;background:#fff;line-height:1.5}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:#F9B040;box-shadow:0 0 0 3px rgba(249,176,64,.1)}
.form-textarea{resize:vertical;min-height:100px}
.form-group{margin-bottom:20px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.form-error{font-size:.8rem;color:#ef4444;margin-top:5px;line-height:1.4}
.form-hint{font-size:.8rem;color:#888;margin-top:5px;line-height:1.4}
.switch{position:relative;display:inline-block;width:44px;height:24px}
.switch input{opacity:0;width:0;height:0}
.switch-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#e0dce8;border-radius:12px;transition:.3s}
.switch-slider::before{content:'';position:absolute;height:18px;width:18px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s}
input:checked+.switch-slider{background:#F9B040}
input:checked+.switch-slider::before{transform:translateX(20px)}

/* ── ALERTS ───────────────────────────────────────────────────────────────── */
.alert{padding:13px 16px;border-radius:8px;font-size:.9rem;line-height:1.5;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px}
.alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}
.alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
.alert-info{background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe}

/* ── PAGINATION ───────────────────────────────────────────────────────────── */
.pagination-wrap{padding:14px 20px;border-top:1px solid #f0f2f5;display:flex;align-items:center;gap:8px;flex-wrap:wrap}

/* ── SEARCH BAR ───────────────────────────────────────────────────────────── */
.admin-search{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:18px}
.admin-search .form-input{max-width:280px}
.filter-select{border:1.5px solid #e0dce8;border-radius:6px;padding:9px 13px;font-family:inherit;font-size:.875rem;color:#333;background:#fff;cursor:pointer;outline:none;line-height:1.4}
.filter-select:focus{border-color:#F9B040}

/* ── IMAGE PREVIEW ────────────────────────────────────────────────────────── */
.img-preview-grid{display:flex;flex-wrap:wrap;gap:10px;margin-top:12px}
.img-preview-item{position:relative;width:96px;height:96px;border-radius:7px;overflow:hidden;border:2px solid #e0dce8}
.img-preview-item img{width:100%;height:100%;object-fit:cover}
.img-preview-remove{position:absolute;top:4px;right:4px;background:rgba(0,0,0,.65);color:#fff;border:none;width:22px;height:22px;border-radius:50%;font-size:.72rem;cursor:pointer;display:flex;align-items:center;justify-content:center}
.upload-area{border:2px dashed #e0dce8;border-radius:8px;padding:32px 20px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s}
.upload-area:hover{border-color:#F9B040;background:#fffbf2}
.upload-area i{font-size:2.2rem;color:#c4c9d4;display:block;margin-bottom:10px}
.upload-area p{font-size:.875rem;color:#888;line-height:1.5}

/* ── STATUS TIMELINE ──────────────────────────────────────────────────────── */
.status-timeline{display:flex;gap:0;overflow-x:auto;padding:6px 0}
.status-step{display:flex;align-items:center;gap:6px;flex-shrink:0}
.status-step-dot{width:11px;height:11px;border-radius:50%;background:#e0dce8;flex-shrink:0}
.status-step.done .status-step-dot{background:#F9B040}
.status-step.current .status-step-dot{background:#100736;box-shadow:0 0 0 3px rgba(16,7,54,.15)}
.status-step-line{width:40px;height:2px;background:#e0dce8;flex-shrink:0}
.status-step.done .status-step-line{background:#F9B040}
.status-step-label{font-size:.7rem;color:#888;white-space:nowrap;line-height:1.4}
.status-step.current .status-step-label{color:#100736;font-weight:700}
.status-step.done .status-step-label{color:#d4880a}

/* ── DASHBOARD GRIDS ──────────────────────────────────────────────────────── */
.dash-secondary-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px}
.dash-charts-row{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px}
.dash-bottom-row{display:grid;grid-template-columns:1fr 1fr;gap:20px}

/* ── MOBILE OVERLAY & HAMBURGER ─────────────────────────────────────────── */
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:499;backdrop-filter:blur(2px)}
.sidebar-overlay.active{display:block}
.topbar-hamburger{display:none;background:none;border:none;cursor:pointer;color:#100736;font-size:1.15rem;padding:8px;margin-right:6px;transition:color .15s;border-radius:6px;min-height:44px;min-width:44px;align-items:center;justify-content:center}
.topbar-hamburger:hover{color:#F9B040;background:#f5f5f5}

/* ── TABLET (≤1024px) ─────────────────────────────────────────────────────── */
@media(max-width:1024px){
  .sidebar{width:224px}
  .admin-main{margin-left:224px}
  .stats-grid{grid-template-columns:repeat(2,1fr)}
  .dash-secondary-grid{grid-template-columns:repeat(2,1fr)}
  .dash-charts-row{grid-template-columns:1fr}
  .dash-bottom-row{grid-template-columns:1fr}
}

/* ── MOBILE (≤768px) ─────────────────────────────────────────────────────── */
@media(max-width:768px){
  body{overflow-x:hidden}

  /* Sidebar — full-height slide-in drawer */
  .sidebar{
    left:-280px;
    width:280px;
    height:100vh;
    height:100dvh;
    top:0;
    box-shadow:none;
  }
  .sidebar.open{
    left:0;
    box-shadow:6px 0 32px rgba(0,0,0,.35);
  }
  .sidebar-logo{padding:22px 18px}
  .sidebar-item{padding:13px 18px;font-size:.95rem;min-height:48px}
  .sidebar-section-label{padding:18px 18px 5px;font-size:.7rem}
  .sidebar-footer{padding:16px 18px}

  /* Main */
  .admin-main{margin-left:0;width:100%}

  /* Topbar */
  .admin-topbar{padding:0 14px;height:58px}
  .topbar-hamburger{display:flex}
  .topbar-title{font-size:.95rem}
  .topbar-badge-text{display:none}
  .topbar-btn{font-size:.95rem}

  /* Content */
  .admin-content{padding:14px 14px 24px}

  /* Stats */
  .stats-grid{grid-template-columns:1fr 1fr;gap:12px}
  .stat-card{padding:16px 16px}
  .stat-val{font-size:1.6rem}
  .dash-secondary-grid{grid-template-columns:1fr 1fr;gap:12px}
  .dash-charts-row{grid-template-columns:1fr}
  .dash-bottom-row{grid-template-columns:1fr}

  /* Page header */
  .page-header{flex-direction:column;align-items:stretch;gap:12px}
  .page-header>div{flex:1}
  .page-header h1{font-size:1.3rem}
  .page-header .btn{width:100%;justify-content:center}

  /* Cards */
  .card{overflow:visible;border-radius:8px}
  .card-header{padding:14px 16px}
  .card-body{padding:16px}

  /* Table */
  .admin-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch}
  table.admin-table{min-width:540px;font-size:.875rem}
  table.admin-table th{padding:11px 14px;font-size:.72rem}
  table.admin-table td{padding:11px 14px}

  /* Forms */
  .form-row{grid-template-columns:1fr}
  .form-input,.form-select,.form-textarea{font-size:.95rem;padding:11px 13px}
  .form-label{font-size:.88rem}
  .form-group{margin-bottom:16px}

  /* Search */
  .admin-search{flex-direction:column;align-items:stretch;gap:10px}
  .admin-search .form-input{max-width:100%}
  .filter-select{width:100%;font-size:.9rem;padding:10px 13px}

  /* Buttons */
  .btn{font-size:.875rem;padding:10px 16px}
  .btn-sm{padding:7px 12px;font-size:.8rem}

  /* Alert */
  .alert{font-size:.875rem}

  /* Status timeline */
  .status-timeline{padding-bottom:10px}
}

/* ── SMALL PHONES (≤480px) ───────────────────────────────────────────────── */
@media(max-width:480px){
  .stats-grid{grid-template-columns:1fr 1fr;gap:10px}
  .stat-card{padding:14px}
  .stat-val{font-size:1.45rem}
  .stat-icon{width:36px;height:36px;font-size:.9rem}
  .dash-secondary-grid{grid-template-columns:1fr 1fr}
  .topbar-badge{display:none}
  .admin-content{padding:12px 12px 20px}
  table.admin-table{min-width:480px;font-size:.82rem}
}

@yield('admin_extra_styles')
</style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
  <div class="sidebar-logo">
    <a href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('assets/extracted/img_01.jpg') }}" alt="My Perfect Stitch Logo" style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;"/>
      <div class="sidebar-logo-text">MPS Admin<span>My Perfect Stitch</span></div>
    </a>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-section-label">Main</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <div class="sidebar-section-label">Catalogue</div>
    <a href="{{ route('admin.products.index') }}" class="sidebar-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}"><i class="fas fa-shopping-bag"></i> Products</a>
    <a href="{{ route('admin.categories.index') }}" class="sidebar-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}"><i class="fas fa-tags"></i> Categories</a>
    <a href="{{ route('admin.gallery.index') }}" class="sidebar-item {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}"><i class="fas fa-images"></i> Gallery</a>
    <a href="{{ route('admin.portfolio.index') }}" class="sidebar-item {{ request()->routeIs('admin.portfolio*') ? 'active' : '' }}"><i class="fas fa-folder-open"></i> Portfolio</a>
    <a href="{{ route('admin.events.index') }}" class="sidebar-item {{ request()->routeIs('admin.events*') ? 'active' : '' }}"><i class="fas fa-calendar"></i> Events</a>
    <a href="{{ route('admin.sliders.index') }}" class="sidebar-item {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}"><i class="fas fa-photo-video"></i> Sliders</a>
    <a href="{{ route('admin.team.index') }}" class="sidebar-item {{ request()->routeIs('admin.team*') ? 'active' : '' }}"><i class="fas fa-users"></i> Team</a>
    <a href="{{ route('admin.clients.index') }}" class="sidebar-item {{ request()->routeIs('admin.clients*') ? 'active' : '' }}"><i class="fas fa-building"></i> Clients</a>

    <div class="sidebar-section-label">Commerce</div>
    <a href="{{ route('admin.quotes.index') }}" class="sidebar-item {{ request()->routeIs('admin.quotes*') ? 'active' : '' }}">
      <i class="fas fa-file-invoice"></i> Quotes
      @php $newQuotes = \App\Models\Quote::where('status','new')->count(); @endphp
      @if($newQuotes > 0)<span style="background:#ef4444;color:#fff;font-size:.6rem;font-weight:700;padding:1px 6px;border-radius:10px;margin-left:auto">{{ $newQuotes }}</span>@endif
    </a>
    <a href="{{ route('admin.orders.index') }}" class="sidebar-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}"><i class="fas fa-box"></i> Orders</a>
    <a href="{{ route('admin.customers.index') }}" class="sidebar-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}"><i class="fas fa-user-friends"></i> Customers</a>
    <a href="{{ route('admin.payments.index') }}" class="sidebar-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> Payments</a>

    @role('admin')
    <div class="sidebar-section-label">Admin</div>
    <a href="{{ route('admin.reports.index') }}" class="sidebar-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Reports</a>
    <a href="{{ route('admin.settings.edit') }}" class="sidebar-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="fas fa-cog"></i> Settings</a>
    <a href="{{ route('admin.users.index') }}" class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i> Users</a>
    @endrole
  </nav>

  <div class="sidebar-footer">
    <a href="{{ route('home') }}" target="_blank"><i class="fas fa-external-link-alt"></i> View Website</a>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:10px">
      @csrf
      <button type="submit">
        <i class="fas fa-sign-out-alt"></i> Log Out
      </button>
    </form>
  </div>
</aside>

{{-- Main --}}
<div class="admin-main">
  <header class="admin-topbar">
    <div style="display:flex;align-items:center;gap:0">
      <button class="topbar-hamburger" id="adminHamburger" onclick="toggleAdminSidebar()" aria-label="Menu">
        <i class="fas fa-bars"></i>
      </button>
      <div class="topbar-title">@yield('topbar_title', 'Dashboard')</div>
    </div>
    <div class="topbar-actions">
      <div class="topbar-badge {{ auth()->user()->isAdmin() ? 'admin' : 'staff' }}">
        <i class="fas fa-{{ auth()->user()->isAdmin() ? 'shield-alt' : 'user' }}"></i>
        <span class="topbar-badge-text">{{ auth()->user()->name }} · {{ auth()->user()->isAdmin() ? 'Admin' : 'Staff' }}</span>
      </div>
      <a href="{{ route('home') }}" class="topbar-btn" title="View site"><i class="fas fa-external-link-alt"></i></a>
    </div>
  </header>

  <div class="admin-content">
    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
      </div>
    @endif

    @yield('content')
  </div>
</div>

{{-- Mobile sidebar overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeAdminSidebar()"></div>

<script>
// Auto-dismiss alerts
setTimeout(() => document.querySelectorAll('.alert').forEach(a => a.style.display = 'none'), 5000);

// Mobile sidebar toggle
function toggleAdminSidebar() {
  const sidebar  = document.querySelector('.sidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const hamburger = document.getElementById('adminHamburger');
  const isOpen = sidebar.classList.toggle('open');
  overlay.classList.toggle('active', isOpen);
  hamburger.innerHTML = isOpen ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
}
function closeAdminSidebar() {
  document.querySelector('.sidebar')?.classList.remove('open');
  document.getElementById('sidebarOverlay')?.classList.remove('active');
  const h = document.getElementById('adminHamburger');
  if (h) h.innerHTML = '<i class="fas fa-bars"></i>';
}
// Close sidebar when a nav link is clicked on mobile
document.querySelectorAll('.sidebar-item').forEach(item => {
  item.addEventListener('click', () => {
    if (window.innerWidth <= 768) closeAdminSidebar();
  });
});
</script>
@yield('scripts')
</body>
</html>
