@extends('layouts.admin')
@section('title','Quotes')
@section('topbar_title','Quote Requests')

@section('content')
<div class="page-header"><div><h1>Quotes</h1><p>Customer quote requests and inquiries</p></div></div>

<div class="card">
  <div class="card-header">
    <form class="admin-search" method="GET">
      <input type="text" name="search" class="form-input" placeholder="Search name, phone, ref…" value="{{ request('search') }}"/>
      <select name="status" class="filter-select">
        <option value="">All Statuses</option>
        @foreach(['new','reviewed','quoted','converted','cancelled'] as $s)
          <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button>
      @if(request()->hasAny(['search','status']))<a href="{{ route('admin.quotes.index') }}" class="btn btn-sm" style="color:#888">Clear</a>@endif
    </form>
    <span style="font-size:.82rem;color:#888">{{ $quotes->total() }} quotes</span>
  </div>
  <div style="overflow-x:auto">
    <table class="admin-table">
      <thead><tr><th>Ref</th><th>Name</th><th>Service</th><th>Budget</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        @forelse($quotes as $quote)
          <tr>
            <td style="font-family:monospace;font-size:.8rem;font-weight:600">{{ $quote->ref }}</td>
            <td>{{ $quote->name }}<br><span style="font-size:.75rem;color:#888">{{ $quote->phone }}</span></td>
            <td style="font-size:.85rem">{{ $quote->service_type }}</td>
            <td style="font-size:.82rem;color:#555">{{ $quote->budget ?? '—' }}</td>
            <td>
              @php $color=['new'=>'blue','reviewed'=>'yellow','quoted'=>'purple','converted'=>'green','cancelled'=>'red'][$quote->status]??'gray'; @endphp
              <span class="badge badge-{{ $color }}">{{ $quote->status }}</span>
            </td>
            <td style="font-size:.8rem;color:#888">{{ $quote->created_at->format('d M Y') }}</td>
            <td><a href="{{ route('admin.quotes.show',$quote) }}" class="btn btn-sm btn-outline"><i class="fas fa-eye"></i></a></td>
          </tr>
        @empty
          <tr><td colspan="7" style="text-align:center;padding:32px;color:#888">No quotes found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="pagination-wrap">{{ $quotes->withQueryString()->links() }}</div>
</div>
@endsection
