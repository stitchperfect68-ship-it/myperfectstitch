<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<style>
body{font-family:Arial,sans-serif;color:#1a1a2e;font-size:13px;margin:0;padding:28px}
.header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;padding-bottom:20px;border-bottom:3px solid #F9B040}
.brand h1{font-size:1.3rem;font-weight:800;color:#100736;margin:0}
.brand p{color:#888;font-size:.8rem;margin:2px 0}
.invoice-meta{text-align:right}
.invoice-meta h2{font-size:1rem;font-weight:700;color:#100736;margin:0 0 4px}
.invoice-meta p{color:#888;font-size:.8rem;margin:2px 0}
.section-title{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#888;margin-bottom:8px;margin-top:24px}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px}
table{width:100%;border-collapse:collapse;margin-top:8px}
th{background:#100736;color:#F9B040;padding:8px 12px;font-size:.75rem;text-align:left;font-weight:700}
td{padding:9px 12px;border-bottom:1px solid #f0f2f5;font-size:.85rem}
.total-row td{font-weight:700;font-size:.95rem;background:#f9f7ff;border-top:2px solid #100736}
.footer{margin-top:40px;padding-top:16px;border-top:1px solid #e8eaf0;font-size:.75rem;color:#aaa;text-align:center}
.badge{display:inline-block;padding:3px 10px;border-radius:12px;font-size:.7rem;font-weight:700}
.badge-green{background:#d1fae5;color:#065f46}
.badge-yellow{background:#fef9c3;color:#854d0e}
</style>
</head>
<body>
<div class="header">
  <div class="brand">
    <h1>My Perfect Stitch</h1>
    <p>Lusaka, Zambia</p>
    <p>+260 968 531 630</p>
    <p>hello@myperfectstitch.com</p>
  </div>
  <div class="invoice-meta">
    <h2>TAX INVOICE</h2>
    <p>{{ $order->ref }}</p>
    <p>Date: {{ $order->created_at->format('d M Y') }}</p>
    <p>Status: <span class="badge {{ $order->payment?->status==='completed'?'badge-green':'badge-yellow' }}">{{ ucfirst($order->payment?->status ?? 'pending') }}</span></p>
  </div>
</div>

<div class="info-grid">
  <div>
    <div class="section-title">Bill To</div>
    <strong>{{ $order->customer_name }}</strong><br>
    {{ $order->customer_phone }}<br>
    @if($order->customer_email){{ $order->customer_email }}<br>@endif
    @if($order->shipping_city){{ $order->shipping_city }}, {{ $order->shipping_country }}@endif
  </div>
  <div>
    <div class="section-title">Payment</div>
    @if($order->payment)
      Gateway: Broadpay Linco<br>
      Amount: K{{ number_format($order->payment->amount,2) }}<br>
      Status: {{ ucfirst($order->payment->status) }}<br>
      @if($order->payment->paid_at)Date: {{ $order->payment->paid_at->format('d M Y') }}@endif
    @endif
  </div>
</div>

<div class="section-title">Order Items</div>
<table>
  <thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead>
  <tbody>
    @foreach($order->items as $item)
      <tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>K{{ number_format($item->price,2) }}</td><td>K{{ number_format($item->subtotal,2) }}</td></tr>
    @endforeach
    <tr class="total-row"><td colspan="3">Total (ZMW)</td><td>K{{ number_format($order->total,2) }}</td></tr>
  </tbody>
</table>

@if($order->notes)
  <div class="section-title" style="margin-top:20px">Notes</div>
  <p style="color:#555;font-size:.85rem">{{ $order->notes }}</p>
@endif

<div class="footer">
  My Perfect Stitch · Lusaka, Zambia · This invoice was generated automatically. Thank you for your business!
</div>
</body>
</html>
