@extends('layouts.app')

@section('title', 'My Orders - My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
@endpush

@section('content')
<div class="acct-hero">
  <h1>My <em>Orders</em></h1>
  <p>Track your purchases and view past orders.</p>
</div>

<section class="acct-page">
  <div class="acct-container">
    @if (session('success'))
      <div class="acct-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="acct-layout">
      @include('customer.partials.sidebar', ['active' => 'orders'])

      <div class="acct-main">
        <div class="acct-stats">
          <div class="acct-stat">
            <div class="acct-stat-val">{{ $stats['total'] }}</div>
            <div class="acct-stat-label">Total Orders</div>
          </div>
          <div class="acct-stat">
            <div class="acct-stat-val">{{ $stats['active'] }}</div>
            <div class="acct-stat-label">Active</div>
          </div>
          <div class="acct-stat">
            <div class="acct-stat-val">K{{ number_format($stats['spent'], 0) }}</div>
            <div class="acct-stat-label">Total Spent</div>
          </div>
        </div>

        <div class="acct-card">
          <div class="acct-head">
            <div>
              <h2>Order History</h2>
              <p>{{ $orders->total() }} {{ \Illuminate\Support\Str::plural('order', $orders->total()) }} placed</p>
            </div>
            <a href="{{ route('shop.index') }}" class="acct-order-link"><i class="fas fa-store"></i> Shop Again</a>
          </div>

          @if ($orders->isEmpty())
            <div class="acct-empty">
              <i class="fas fa-shopping-bag"></i>
              <h3>No orders yet</h3>
              <p>When you place an order it will appear here.</p>
              <a href="{{ route('shop.index') }}" class="acct-pay-link">Browse Products</a>
            </div>
          @else
            @foreach ($orders as $order)
              <div class="acct-order">
                <div class="acct-order-icon"><i class="fas fa-box"></i></div>
                <div class="acct-order-info">
                  <div class="acct-order-ref">{{ $order->ref }}</div>
                  <div class="acct-order-meta">
                    {{ $order->created_at->format('d M Y') }}
                    &middot; {{ $order->items->count() }} {{ \Illuminate\Support\Str::plural('item', $order->items->count()) }}
                    @if ($order->payment)
                      &middot; {{ ucfirst($order->payment->status) }}
                    @endif
                  </div>
                </div>
                <div class="acct-order-right">
                  <div class="acct-order-amount">K{{ number_format($order->total, 0) }}</div>
                  <span class="acct-badge acct-badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
                <a href="{{ route('customer.order.show', $order->ref) }}" class="acct-order-link">
                  View <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            @endforeach

            @include('partials.pagination', ['paginator' => $orders])
          @endif
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
