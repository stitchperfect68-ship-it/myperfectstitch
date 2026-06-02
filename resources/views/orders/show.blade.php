@extends('layouts.app')
@section('title', 'Order '.$order->ref.' — My Perfect Stitch')

@section('content')
<div style="background:#100736;padding:100px 5% 40px;text-align:center;border-bottom:3px solid #F9B040">
  <div style="font-size:.7rem;letter-spacing:.2em;text-transform:uppercase;color:rgba(249,176,64,.7);font-weight:700;margin-bottom:10px">Order Tracking</div>
  <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:700;color:#fff">Order <em style="color:#F9B040;font-style:italic">{{ $order->ref }}</em></h1>
</div>

<section style="padding:60px 5%;background:var(--cream)">
  <div style="max-width:760px;margin:0 auto">

    @if(session('success'))
      <div style="background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;padding:16px 20px;border-radius:6px;margin-bottom:28px;font-size:.9rem;display:flex;align-items:center;gap:10px">
        <i class="fas fa-check-circle fa-lg"></i> {{ session('success') }}
      </div>
    @endif

    <div style="background:#fff;border-radius:6px;padding:32px;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:24px">
      <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:12px;margin-bottom:24px;padding-bottom:20px;border-bottom:2px solid #e8e4f0">
        <div>
          <div style="font-size:.72rem;color:#888;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Order Reference</div>
          <div style="font-size:1.2rem;font-weight:700;color:#100736">{{ $order->ref }}</div>
        </div>
        <span style="background:{{ match($order->status){ 'paid','delivered'=>'#d1fae5', 'pending_payment','processing'=>'#fef9c3', 'cancelled','refunded'=>'#fee2e2', default=>'#e8e4f0' } }};color:{{ match($order->status){ 'paid','delivered'=>'#065f46', 'pending_payment','processing'=>'#854d0e', 'cancelled','refunded'=>'#991b1b', default=>'#555' } }};padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em">
          {{ $order->status_label }}
        </span>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;font-size:.88rem">
        <div><div style="color:#888;font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Customer</div><div style="font-weight:600">{{ $order->customer_name }}</div><div style="color:#666">{{ $order->customer_phone }}</div></div>
        <div><div style="color:#888;font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Placed On</div><div style="font-weight:600">{{ $order->created_at->format('d M Y') }}</div></div>
      </div>

      <table style="width:100%;border-collapse:collapse">
        <thead><tr style="background:#f9f7ff"><th style="padding:10px 14px;font-size:.75rem;font-weight:700;color:#555;text-align:left;text-transform:uppercase;letter-spacing:.08em">Item</th><th style="padding:10px 14px;text-align:center;font-size:.75rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.08em">Qty</th><th style="padding:10px 14px;text-align:right;font-size:.75rem;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.08em">Subtotal</th></tr></thead>
        <tbody>
          @foreach($order->items as $item)
            <tr style="border-bottom:1px solid #f0eef8"><td style="padding:12px 14px;font-size:.9rem;font-weight:600">{{ $item->product_name }}</td><td style="padding:12px 14px;text-align:center;font-size:.88rem;color:#555">{{ $item->quantity }}</td><td style="padding:12px 14px;text-align:right;font-weight:700;font-size:.95rem">K{{ number_format($item->subtotal,0) }}</td></tr>
          @endforeach
          <tr><td colspan="2" style="padding:14px;font-weight:700;color:#100736;font-size:1rem">Total</td><td style="padding:14px;text-align:right;font-weight:800;font-size:1.1rem;color:#100736">K{{ number_format($order->total,0) }}</td></tr>
        </tbody>
      </table>

      @if($order->payment)
        <div style="margin-top:20px;background:#f9f7ff;border-radius:4px;padding:14px 16px;font-size:.85rem">
          <span style="font-weight:700;color:#100736">Payment:</span>
          <span style="margin-left:8px;color:{{ $order->payment->status === 'completed' ? '#065f46' : '#888' }}">{{ ucfirst($order->payment->status) }}</span>
          @if($order->payment->paid_at)<span style="margin-left:8px;color:#888"> · Paid on {{ $order->payment->paid_at->format('d M Y, H:i') }}</span>@endif
        </div>
      @endif
    </div>

    <div style="text-align:center">
      <a href="{{ route('home') }}" style="color:#888;font-size:.88rem;text-decoration:none">← Back to Home</a>
    </div>
  </div>
</section>
@endsection
