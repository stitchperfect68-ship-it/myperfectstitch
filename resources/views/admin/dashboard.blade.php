@extends('layouts.admin')
@section('title','Dashboard')
@section('topbar_title','Dashboard')

@section('content')
{{-- Stats --}}
<div class="stats-grid">
  <div class="stat-card gold">
    <div class="stat-icon gold"><i class="fas fa-box"></i></div>
    <div class="stat-val">{{ number_format($stats['orders_this_month']) }}</div>
    <div class="stat-lbl">Orders This Month</div>
  </div>
  <div class="stat-card navy">
    <div class="stat-icon"><i class="fas fa-coins"></i></div>
    <div class="stat-val">K{{ number_format($stats['revenue_this_month'],0) }}</div>
    <div class="stat-lbl">Revenue This Month</div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><i class="fas fa-file-invoice"></i></div>
    <div class="stat-val">{{ $stats['pending_quotes'] }}</div>
    <div class="stat-lbl">Pending Quotes</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon"><i class="fas fa-users"></i></div>
    <div class="stat-val">{{ number_format($stats['total_customers']) }}</div>
    <div class="stat-lbl">Total Customers</div>
  </div>
</div>

{{-- Secondary stats --}}
<div class="dash-secondary-grid">
  <div style="background:#fff;border-radius:8px;padding:16px 18px;box-shadow:0 1px 4px rgba(0,0,0,.05)">
    <div style="font-size:1.5rem;font-weight:800;color:#100736">{{ number_format($stats['total_orders']) }}</div>
    <div style="font-size:.75rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:3px">Total Orders</div>
  </div>
  <div style="background:#fff;border-radius:8px;padding:16px 18px;box-shadow:0 1px 4px rgba(0,0,0,.05)">
    <div style="font-size:1.5rem;font-weight:800;color:#100736">K{{ number_format($stats['total_revenue'],0) }}</div>
    <div style="font-size:.75rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:3px">Total Revenue</div>
  </div>
  <div style="background:#fff;border-radius:8px;padding:16px 18px;box-shadow:0 1px 4px rgba(0,0,0,.05)">
    <div style="font-size:1.5rem;font-weight:800;color:#100736">{{ number_format($stats['total_products']) }}</div>
    <div style="font-size:.75rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:3px">Products</div>
  </div>
  <div style="background:#fff;border-radius:8px;padding:16px 18px;box-shadow:0 1px 4px rgba(0,0,0,.05);border-left:4px solid {{ $stats['low_stock'] > 0 ? '#ef4444' : '#10b981' }}">
    <div style="font-size:1.5rem;font-weight:800;color:{{ $stats['low_stock'] > 0 ? '#ef4444' : '#10b981' }}">{{ $stats['low_stock'] }}</div>
    <div style="font-size:.75rem;color:#888;text-transform:uppercase;letter-spacing:.08em;margin-top:3px">Low Stock</div>
  </div>
</div>

{{-- Charts Row --}}
<div class="dash-charts-row">
  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-chart-line" style="color:#F9B040;margin-right:6px"></i>Revenue — {{ date('Y') }}</h3>
    </div>
    <div class="card-body">
      <canvas id="revenueChart" height="80"></canvas>
    </div>
  </div>
  <div class="card">
    <div class="card-header">
      <h3><i class="fas fa-chart-doughnut" style="color:#F9B040;margin-right:6px"></i>Orders by Status</h3>
    </div>
    <div class="card-body" style="display:flex;align-items:center;justify-content:center">
      <canvas id="statusChart" width="200" height="200"></canvas>
    </div>
  </div>
</div>

{{-- Tables Row --}}
<div class="dash-bottom-row">
  {{-- Recent Orders --}}
  <div class="card">
    <div class="card-header">
      <h3>Recent Orders</h3>
      <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline">View All</a>
    </div>
    <div style="overflow-x:auto">
      <table class="admin-table">
        <thead><tr><th>Ref</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
          @foreach($recentOrders as $order)
            <tr>
              <td><a href="{{ route('admin.orders.show',$order) }}" style="color:#100736;font-weight:600;font-family:monospace;font-size:.8rem">{{ $order->ref }}</a></td>
              <td>{{ $order->customer_name }}<br><span style="font-size:.75rem;color:#888">{{ $order->customer_phone }}</span></td>
              <td style="font-weight:700">K{{ number_format($order->total,0) }}</td>
              <td><span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
              <td style="font-size:.8rem;color:#888">{{ $order->created_at->format('d M') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pending Quotes --}}
  <div class="card">
    <div class="card-header">
      <h3>Pending Quotes</h3>
      <a href="{{ route('admin.quotes.index') }}" class="btn btn-sm btn-outline">View All</a>
    </div>
    @if($pendingQuotes->isEmpty())
      <div style="padding:28px;text-align:center;color:#888;font-size:.85rem">No pending quotes</div>
    @else
      <div style="overflow-x:auto">
        <table class="admin-table">
          <thead><tr><th>Name</th><th>Service</th><th>Date</th></tr></thead>
          <tbody>
            @foreach($pendingQuotes as $quote)
              <tr>
                <td><a href="{{ route('admin.quotes.show',$quote) }}" style="color:#100736;font-weight:600">{{ $quote->name }}</a><br><span style="font-size:.75rem;color:#888">{{ $quote->phone }}</span></td>
                <td style="font-size:.8rem">{{ $quote->service_type }}</td>
                <td style="font-size:.78rem;color:#888">{{ $quote->created_at->format('d M') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

{{-- Top Products --}}
@if($topProducts->count())
<div class="card" style="margin-top:20px">
  <div class="card-header"><h3><i class="fas fa-trophy" style="color:#F9B040;margin-right:6px"></i>Top Selling Products</h3></div>
  <div style="overflow-x:auto">
    <table class="admin-table">
      <thead><tr><th>#</th><th>Product</th><th>Units Sold</th><th>Revenue</th></tr></thead>
      <tbody>
        @foreach($topProducts as $i => $p)
          <tr>
            <td style="font-weight:800;color:{{ $i===0?'#F9B040':($i===1?'#888':'#aaa') }};font-size:1.1rem">{{ $i+1 }}</td>
            <td style="font-weight:600">{{ $p->product_name }}</td>
            <td>{{ number_format($p->total_sold) }}</td>
            <td style="font-weight:700">K{{ number_format($p->revenue,0) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection

@section('scripts')
<script>
// Revenue Chart
const revData = @json($revenueChart);
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const revenues = months.map((_,i) => revData[i+1]?.total || 0);

new Chart(document.getElementById('revenueChart'), {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      label: 'Revenue (ZMW)',
      data: revenues,
      borderColor: '#F9B040',
      backgroundColor: 'rgba(249,176,64,.08)',
      borderWidth: 2.5,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#F9B040',
      pointRadius: 4,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, grid: { color: '#f0f2f5' }, ticks: { callback: v => 'K'+v.toLocaleString(), font: { size: 11 } } },
      x: { grid: { display: false }, ticks: { font: { size: 11 } } }
    }
  }
});

// Status Doughnut
const statusData = @json($orderStatusChart);
const statusColors = { pending_payment:'#fbbf24', paid:'#3b82f6', processing:'#6366f1', ready:'#8b5cf6', dispatched:'#f97316', delivered:'#10b981', cancelled:'#ef4444', refunded:'#9ca3af' };
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: Object.keys(statusData).map(k => k.replace('_',' ')),
    datasets: [{
      data: Object.values(statusData),
      backgroundColor: Object.keys(statusData).map(k => statusColors[k] || '#e5e7eb'),
      borderWidth: 2,
      borderColor: '#fff'
    }]
  },
  options: {
    responsive: false,
    plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 8 } } },
    cutout: '65%'
  }
});
</script>
@endsection
