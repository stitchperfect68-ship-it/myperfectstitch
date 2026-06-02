@extends('layouts.admin')
@section('title','Quote '.$quote->ref)
@section('topbar_title','Quote Detail')

@section('content')
<div class="page-header">
  <div><h1>{{ $quote->ref }}</h1><p>Submitted {{ $quote->created_at->diffForHumans() }}</p></div>
  <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;align-items:start">
  <div>
    <div class="card">
      <div class="card-header"><h3>Quote Details</h3></div>
      <div class="card-body" style="font-size:.9rem">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Service Type</div><div style="font-weight:600">{{ $quote->service_type }}</div></div>
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Quantity</div><div style="font-weight:600">{{ $quote->quantity ?? '—' }}</div></div>
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Budget</div><div style="font-weight:600">{{ $quote->budget ?? '—' }}</div></div>
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Deadline</div><div style="font-weight:600">{{ $quote->deadline ?? '—' }}</div></div>
        </div>
        @if($quote->description)
          <div style="margin-bottom:16px">
            <div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px">Description</div>
            <div style="background:#f9f7ff;padding:14px;border-radius:6px;line-height:1.7;color:#444">{{ $quote->description }}</div>
          </div>
        @endif
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Contact Name</div><div style="font-weight:600">{{ $quote->name }}</div></div>
          <div><div style="color:#888;font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">Phone</div><div style="font-weight:600">{{ $quote->phone }}</div></div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="card">
      <div class="card-header"><h3>Update Quote</h3></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.quotes.update',$quote) }}">
          @csrf @method('PUT')
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              @foreach(['new','reviewed','quoted','converted','cancelled'] as $s)
                <option value="{{ $s }}" {{ $quote->status===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Quoted Amount (ZMW)</label>
            <input type="number" name="quoted_amount" class="form-input" step="0.01" value="{{ $quote->quoted_amount }}"/>
          </div>
          <div class="form-group">
            <label class="form-label">Admin Notes</label>
            <textarea name="admin_notes" class="form-textarea" rows="4">{{ $quote->admin_notes }}</textarea>
          </div>
          <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center">Update Quote</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h3>Actions</h3></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
        <a href="https://wa.me/{{ $quote->phone }}?text={{ urlencode('Hello '.$quote->name.'! Thank you for your quote request to My Perfect Stitch (Ref: '.$quote->ref.'). We\'re reviewing it and will get back to you shortly.') }}" target="_blank" class="btn btn-success" style="justify-content:center"><i class="fab fa-whatsapp"></i> WhatsApp Customer</a>
        @if($quote->quoted_amount)
          <form method="POST" action="{{ route('admin.quotes.send-payment-link',$quote) }}">
            @csrf
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center"><i class="fas fa-link"></i> Generate Payment Link</button>
          </form>
          <form method="POST" action="{{ route('admin.quotes.convert-to-order',$quote) }}" onsubmit="return confirm('Convert this quote to an order?')">
            @csrf
            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center"><i class="fas fa-exchange-alt"></i> Convert to Order</button>
          </form>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
