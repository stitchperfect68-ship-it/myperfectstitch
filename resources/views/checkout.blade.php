@extends('layouts.app')
@section('title', 'Checkout — My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}"/>
@endpush

@section('content')
<div class="page-hero"><h1>Secure <em>Checkout</em></h1></div>

<section class="checkout-section">
  <div class="checkout-inner">
    <div class="checkout-form">
      <h3><i class="fas fa-user" style="color:#F9B040;margin-right:8px"></i>Your Details</h3>
      @if($errors->any())
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 16px;border-radius:4px;margin-bottom:20px;font-size:.88rem">
          <ul style="margin:0;padding-left:16px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif
      <form method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <div class="form-row">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $customer?->name) }}" required/>
          </div>
          <div class="form-group">
            <label>Phone / WhatsApp *</label>
            <input type="tel" name="phone" value="{{ old('phone', $customer?->phone) }}" required/>
          </div>
        </div>
        <div class="form-group">
          <label>Email (Optional)</label>
          <input type="email" name="email" value="{{ old('email', $customer?->email) }}"/>
        </div>
        <div class="form-group">
          <label>Delivery Address (Optional)</label>
          <input type="text" name="street" placeholder="Street / area" value="{{ old('street') }}"/>
        </div>
        <div class="form-row">
          <div class="form-group">
            <input type="text" name="city" placeholder="City" value="{{ old('city') }}"/>
          </div>
          <div class="form-group">
            <input type="text" name="country" value="Zambia" readonly style="background:#f9f7ff;color:#888"/>
          </div>
        </div>
        <div class="form-group">
          <label>Notes (Optional)</label>
          <textarea name="notes" rows="2" placeholder="Special instructions…">{{ old('notes') }}</textarea>
        </div>
        <button type="submit" class="btn-pay">
          <i class="fas fa-lock"></i>
          Pay K{{ number_format($cart->total(),0) }} via Broadpay Linco
        </button>
        <div class="linco-badge">
          <i class="fas fa-shield-alt"></i>
          Secured by Broadpay Linco · ZMW
        </div>
      </form>
    </div>

    <div class="order-summary">
      <h3><i class="fas fa-shopping-bag" style="color:#F9B040;margin-right:8px"></i>Order Summary</h3>
      @foreach($cart->all() as $item)
        <div class="summary-item">
          <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
          <span style="font-weight:600">K{{ number_format($item['price'] * $item['quantity'],0) }}</span>
        </div>
      @endforeach
      <div class="summary-total">
        <span>Total</span>
        <span>K{{ number_format($cart->total(),0) }}</span>
      </div>
    </div>
  </div>
</section>
@endsection
