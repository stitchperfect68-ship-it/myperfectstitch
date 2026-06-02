@extends('layouts.admin')
@section('title','Orders')
@section('topbar_title','Orders')

@section('content')
<div class="page-header">
  <div><h1>Orders</h1><p>Manage customer orders and track fulfillment</p></div>
  <a href="{{ route('admin.reports.export') }}" class="btn btn-outline"><i class="fas fa-download"></i> Export CSV</a>
</div>

<div class="card">
  <div class="card-header">
    <form class="admin-search" method="GET">
      <input type="text" name="search" class="form-input" placeholder="Search ref, name, phone…" value="{{ request('search') }}"/>
      <select name="status" class="filter-select">
        <option value="">All Statuses</option>
        @foreach(\App\Models\Order::$statuses as $key => $label)
          <option value="{{ $key }}" {{ request('status')===$key?'selected':'' }}>{{ $label }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button>
      @if(request()->hasAny(['search','status']))<a href="{{ route('admin.orders.index') }}" class="btn btn-sm" style="color:#888">Clear</a>@endif
    </form>
    <span style="font-size:.82rem;color:#888">{{ $orders->total() }} orders</span>
  </div>
  <div style="overflow-x:auto">
    <table class="admin-table">
      <thead><tr><th>Ref</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        @forelse($orders as $order)
          <tr>
            <td><a href="{{ route('admin.orders.show',$order) }}" style="color:#100736;font-weight:700;font-family:monospace;font-size:.82rem">{{ $order->ref }}</a></td>
            <td>{{ $order->customer_name }}<br><span style="font-size:.75rem;color:#888">{{ $order->customer_phone }}</span></td>
            <td style="color:#555;font-size:.85rem">{{ $order->items->count() }} item(s)</td>
            <td style="font-weight:700">K{{ number_format($order->total,0) }}</td>
            <td>
              @if($order->payment)
                <span class="badge {{ $order->payment->status==='completed'?'badge-green':($order->payment->status==='pending'?'badge-yellow':'badge-red') }}">{{ $order->payment->status }}</span>
              @else <span class="badge badge-gray">—</span> @endif
            </td>
            <td><span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
            <td style="font-size:.8rem;color:#888">{{ $order->created_at->format('d M Y') }}</td>
            <td><a href="{{ route('admin.orders.show',$order) }}" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i></a></td>
          </tr>
        @empty
          <tr><td colspan="8" style="text-align:center;padding:32px;color:#888">No orders found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination-wrap">{{ $orders->withQueryString()->links() }}</div>
</div>
@endsection
