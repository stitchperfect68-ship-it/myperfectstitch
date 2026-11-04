@extends('layouts.app')

@section('title', 'Order ' . $order->ref . ' - My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
@endpush

@section('content')
@php
  $orderFlow = ['pending_payment', 'paid', 'processing', 'ready', 'dispatched', 'delivered'];
  $currentIndex = array_search($order->status, $orderFlow, true);
  $isClosed = in_array($order->status, ['cancelled', 'refunded'], true);
  $whatsappNumber = \App\Models\Setting::get('whatsapp_number', '260968531630');
  $method = $order->payment?->payment_method;
  $methodLabel = match (true) {
      $method === 'card' => 'Card',
      str_starts_with((string) $method, 'mobile_money:') => 'Mobile Money - ' . ucfirst(str_replace('mobile_money:', '', $method)),
      default => $method ? ucfirst($method) : '—',
  };
@endphp

<div class="acct-hero">
  <h1>Order <em>{{ $order->ref }}</em></h1>
  <p>Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
</div>

<section class="acct-page">
  <div class="acct-container">
    <div class="acct-layout">
      @include('customer.partials.sidebar', ['active' => 'orders'])

      <div class="acct-main">
        @if (session('success'))
          <div class="acct-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <div class="acct-card">
          <div class="acct-head">
            <div>
              <h2>Order Status</h2>
              <p>Current status: <strong style="color:#100736">{{ $order->status_label }}</strong></p>
            </div>
            <span class="acct-badge acct-badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
          </div>

          @if ($isClosed)
            <div class="acct-alert error">
              <i class="fas fa-info-circle"></i>
              This order has been {{ $order->status }}. Please contact us if you need help.
            </div>
          @else
            <div class="acct-timeline">
              @foreach ($orderFlow as $index => $step)
                @php
                  $state = '';
                  if ($currentIndex !== false) {
                      if ($index < $currentIndex) {
                          $state = 'done';
                      } elseif ($index === $currentIndex) {
                          $state = 'done current';
                      }
                  }
                @endphp
                <div class="acct-step {{ $state }}">
                  {{ \App\Models\Order::$statuses[$step] }}
                </div>
              @endforeach
            </div>
          @endif

          @if ($order->status === 'pending_payment' && $order->payment)
            <div class="acct-actions">
              @if ($order->payment->status === 'failed')
                <div class="acct-alert error" style="margin:0">
                  <i class="fas fa-exclamation-circle"></i> Your payment failed. Please try again or contact us.
                </div>
              @elseif ($method === 'card')
                <a href="{{ route('payment.card', $order->ref) }}" class="acct-pay-link"><i class="fas fa-credit-card"></i> Complete Card Payment</a>
              @else
                <a href="{{ route('payment.pending', $order->ref) }}" class="acct-pay-link"><i class="fas fa-mobile-alt"></i> Complete Mobile Money Payment</a>
              @endif
            </div>
          @endif
        </div>

        <div class="acct-card">
          <div class="acct-head">
            <h2>Items</h2>
          </div>
          <div style="overflow-x:auto">
            <table class="acct-table">
              <thead>
                <tr>
                  <th>Item</th>
                  <th class="num">Qty</th>
                  <th class="num">Unit Price</th>
                  <th class="num">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($order->items as $item)
                  <tr>
                    <td>
                      <strong style="color:#100736">{{ $item->product_name }}</strong>
                      @if ($item->product)
                        <div class="acct-muted"><a href="{{ route('shop.show', $item->product) }}" style="color:#999">View product</a></div>
                      @endif
                    </td>
                    <td class="num">{{ $item->quantity }}</td>
                    <td class="num">K{{ number_format($item->price, 0) }}</td>
                    <td class="num">K{{ number_format($item->subtotal, 0) }}</td>
                  </tr>
                @endforeach
                <tr>
                  <td colspan="3" class="num">Subtotal</td>
                  <td class="num">K{{ number_format($order->subtotal, 0) }}</td>
                </tr>
                <tr class="acct-total-row">
                  <td colspan="3" class="num">Total</td>
                  <td class="num">K{{ number_format($order->total, 0) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="acct-card">
          <div class="acct-head">
            <h2>Details</h2>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;font-size:.85rem">
            <div>
              <div class="acct-muted" style="text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:6px">Customer</div>
              <div style="font-weight:600;color:#100736">{{ $order->customer_name }}</div>
              <div style="color:#666">{{ $order->customer_phone }}</div>
              @if ($order->customer_email)<div style="color:#666">{{ $order->customer_email }}</div>@endif
            </div>
            <div>
              <div class="acct-muted" style="text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:6px">Delivery</div>
              @if ($order->shipping_street || $order->shipping_city)
                <div style="color:#666">{{ $order->shipping_street }}</div>
                <div style="color:#666">{{ $order->shipping_city }}{{ $order->shipping_city && $order->shipping_country ? ', ' : '' }}{{ $order->shipping_country }}</div>
              @else
                <div style="color:#999">No delivery address provided.</div>
              @endif
            </div>
            <div>
              <div class="acct-muted" style="text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:6px">Payment</div>
              <div style="color:#666">{{ $methodLabel }}</div>
              @if ($order->payment)
                <div style="color:{{ $order->payment->status === 'completed' ? '#065f46' : '#666' }};font-weight:600">{{ ucfirst($order->payment->status) }}</div>
                @if ($order->payment->paid_at)
                  <div style="color:#999">Paid {{ $order->payment->paid_at->format('d M Y, H:i') }}</div>
                @endif
              @endif
            </div>
            <div>
              <div class="acct-muted" style="text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:6px">Need Help?</div>
              <a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode('Hi, I need help with order ' . $order->ref) }}" target="_blank" rel="noopener" style="color:#25D366;font-weight:700;text-decoration:none">
                <i class="fab fa-whatsapp"></i> Chat with us
              </a>
            </div>
          </div>

          @if ($order->notes)
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid #f0eef8">
              <div class="acct-muted" style="text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:6px">Order Notes</div>
              <p style="font-size:.85rem;color:#555;line-height:1.6">{{ $order->notes }}</p>
            </div>
          @endif
        </div>

        <div class="acct-actions">
          <a href="{{ route('customer.orders') }}" class="acct-order-link"><i class="fas fa-arrow-left"></i> Back to Orders</a>
          <a href="{{ route('shop.index') }}" class="acct-order-link"><i class="fas fa-store"></i> Continue Shopping</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
