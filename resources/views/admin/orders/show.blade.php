@extends('layouts.admin')
@section('title','Order '.$order->ref)
@section('topbar_title','Order Detail')

@section('content')
<div class="page-header">
  <div>
    <h1>{{ $order->ref }}</h1>
    <p>Placed {{ $order->created_at->format('d M Y, H:i') }}</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('admin.orders.invoice',$order) }}" class="btn btn-outline"><i class="fas fa-file-pdf"></i> Invoice PDF</a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
  </div>
</div>

<div class="dash-bottom-row" style="align-items:start">
  <div>
    <div class="card">
      <div class="card-header"><h3>Order Items</h3></div>
      <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
          @foreach($order->items as $item)
            <tr>
              <td style="font-weight:600">{{ $item->product_name }}</td>
              <td>K{{ number_format($item->price,0) }}</td>
              <td>{{ $item->quantity }}</td>
              <td style="font-weight:700">K{{ number_format($item->subtotal,0) }}</td>
            </tr>
          @endforeach
          <tr style="background:#f9f7ff">
            <td colspan="3" style="font-weight:700;color:#100736">Total</td>
            <td style="font-weight:800;font-size:1.1rem;color:#100736">K{{ number_format($order->total,0) }}</td>
          </tr>
        </tbody>
      </table>
      </div>
    </div>

    @if($order->payment)
    <div class="card">
      <div class="card-header"><h3>Payment</h3></div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:16px;font-size:.88rem">
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Gateway</div><div style="font-weight:600">Broadpay Linco</div></div>
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Amount</div><div style="font-weight:700">K{{ number_format($order->payment->amount,0) }}</div></div>
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Status</div><span class="badge badge-{{ $order->payment->status==='completed'?'green':($order->payment->status==='pending'?'yellow':'red') }}">{{ $order->payment->status }}</span></div>
        </div>
        @if($order->payment->transaction_id)
          <div style="margin-top:12px;font-size:.82rem;color:#888">Transaction ID: <code>{{ $order->payment->transaction_id }}</code></div>
        @endif
      </div>
    </div>
    @endif
  </div>

  <div>
    <div class="card">
      <div class="card-header"><h3>Update Status</h3></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.orders.status',$order) }}">
          @csrf @method('PUT')
          <div class="form-group">
            <label class="form-label">Order Status</label>
            <select name="status" class="form-select">
              @foreach(\App\Models\Order::$statuses as $key => $label)
                <option value="{{ $key }}" {{ $order->status===$key?'selected':'' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center">Update Status</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3>Customer</h3></div>
      <div class="card-body" style="font-size:.88rem">
        <div style="font-weight:700;margin-bottom:4px">{{ $order->customer_name }}</div>
        <div style="color:#888;margin-bottom:4px">{{ $order->customer_phone }}</div>
        @if($order->customer_email)<div style="color:#888">{{ $order->customer_email }}</div>@endif
        @if($order->shipping_street || $order->shipping_city)
          <div style="margin-top:12px;padding-top:12px;border-top:1px solid #f0f2f5">
            <div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Delivery Address</div>
            <div>{{ $order->shipping_street }}</div>
            <div>{{ $order->shipping_city }}, {{ $order->shipping_country }}</div>
          </div>
        @endif
      </div>
    </div>

    @if($order->notes)
    <div class="card">
      <div class="card-header"><h3>Notes</h3></div>
      <div class="card-body" style="font-size:.88rem;color:#555">{{ $order->notes }}</div>
    </div>
    @endif
  </div>
</div>
@endsection
