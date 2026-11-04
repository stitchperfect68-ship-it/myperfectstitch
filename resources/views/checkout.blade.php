@extends('layouts.app')
@section('title', 'Checkout — My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}"/>
<style>
.payment-section{margin-top:24px;padding-top:24px;border-top:2px solid #f0eef8}
.payment-section h4{font-size:.9rem;font-weight:700;color:#100736;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.operator-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:16px}
.operator-card{position:relative}
.operator-card input{position:absolute;opacity:0;width:0;height:0}
.operator-card label{display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 8px;border:2px solid #e0dce8;border-radius:6px;cursor:pointer;transition:border-color .2s,background .2s;text-align:center}
.operator-card label .op-name{font-size:.78rem;font-weight:700;color:#100736}
.operator-card label .op-sub{font-size:.68rem;color:#888}
.operator-card input:checked + label{border-color:#F9B040;background:#fffbf0}
.operator-card label:hover{border-color:#F9B040}
.op-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;color:#fff}
.op-airtel{background:#e2001a}
.op-mtn{background:#ffcb00;color:#1a1a1a}
.op-zamtel{background:#00843d}
.momo-notice{background:#fffbf0;border:1px solid #F9B040;border-radius:4px;padding:10px 14px;font-size:.78rem;color:#7a5a00;display:flex;align-items:flex-start;gap:8px;margin-top:4px}
.momo-notice i{margin-top:2px;flex-shrink:0}
.pay-methods{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:18px}
.pay-method-card{position:relative}
.pay-method-card input{position:absolute;opacity:0;width:0;height:0}
.pay-method-card label{display:flex;align-items:center;gap:12px;padding:14px 16px;border:2px solid #e0dce8;border-radius:6px;cursor:pointer;transition:border-color .2s,background .2s}
.pay-method-card label .pm-icon{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.95rem;color:#fff;background:#100736;flex-shrink:0}
.pay-method-card label .pm-title{font-size:.85rem;font-weight:700;color:#100736;display:block}
.pay-method-card label .pm-sub{font-size:.7rem;color:#888;display:block}
.pay-method-card input:checked + label{border-color:#F9B040;background:#fffbf0}
.pay-method-card label:hover{border-color:#F9B040}
.card-notice{background:#f0f7ff;border:1px solid #b6d4fe;border-radius:4px;padding:10px 14px;font-size:.78rem;color:#084298;display:flex;align-items:flex-start;gap:8px}
.card-notice i{margin-top:2px;flex-shrink:0}
</style>
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

      @if(session('error'))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:12px 16px;border-radius:4px;margin-bottom:20px;font-size:.88rem">
          {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="{{ route('checkout.process') }}">
        @csrf

        {{-- Contact details --}}
        <div class="form-row">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $customer?->name) }}" required/>
          </div>
          <div class="form-group">
            <label>Phone / WhatsApp *</label>
            <input type="tel" name="phone" id="phoneField" value="{{ old('phone', $customer?->phone) }}" required/>
          </div>
        </div>
        <div class="form-group">
          <label>Email <span id="emailHint" style="font-weight:400;color:#888">(Optional)</span></label>
          <input type="email" name="email" id="emailField" value="{{ old('email', $customer?->email) }}"/>
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

        {{-- Payment method --}}
        <div class="payment-section">
          <h4><i class="fas fa-credit-card" style="color:#F9B040"></i> Payment Method</h4>

          <div class="pay-methods" style="grid-template-columns:{{ ($cardEnabled ?? false) ? '1fr 1fr' : '1fr' }}">
            <div class="pay-method-card">
              <input type="radio" name="payment_method" id="pm_momo" value="mobile_money"
                {{ old('payment_method', 'mobile_money') === 'mobile_money' ? 'checked' : '' }}/>
              <label for="pm_momo">
                <span class="pm-icon" style="background:#F9B040"><i class="fas fa-mobile-alt"></i></span>
                <span>
                  <span class="pm-title">Mobile Money</span>
                  <span class="pm-sub">Airtel · MTN · Zamtel</span>
                </span>
              </label>
            </div>
            @if($cardEnabled ?? false)
              <div class="pay-method-card">
                <input type="radio" name="payment_method" id="pm_card" value="card"
                  {{ old('payment_method') === 'card' ? 'checked' : '' }}/>
                <label for="pm_card">
                  <span class="pm-icon" style="background:#100736"><i class="fas fa-credit-card"></i></span>
                  <span>
                    <span class="pm-title">Card</span>
                    <span class="pm-sub">Visa · Mastercard</span>
                  </span>
                </label>
              </div>
            @endif
          </div>

          {{-- Mobile Money --}}
          <div id="momoSection">
            <p style="font-size:.82rem;color:#555;margin-bottom:14px">
              Select your network and confirm the mobile money number to charge.
            </p>

          {{-- Operator selection --}}
          <div class="operator-grid">
            <div class="operator-card">
              <input type="radio" name="momo_operator" id="op_airtel" value="airtel"
                {{ old('momo_operator','airtel') === 'airtel' ? 'checked' : '' }} required/>
              <label for="op_airtel">
                <span class="op-icon op-airtel">AM</span>
                <span class="op-name">Airtel Money</span>
                <span class="op-sub">Airtel Zambia</span>
              </label>
            </div>
            <div class="operator-card">
              <input type="radio" name="momo_operator" id="op_mtn" value="mtn"
                {{ old('momo_operator') === 'mtn' ? 'checked' : '' }}/>
              <label for="op_mtn">
                <span class="op-icon op-mtn">MTN</span>
                <span class="op-name">MTN MoMo</span>
                <span class="op-sub">MTN Zambia</span>
              </label>
            </div>
            <div class="operator-card">
              <input type="radio" name="momo_operator" id="op_zamtel" value="zamtel"
                {{ old('momo_operator') === 'zamtel' ? 'checked' : '' }}/>
              <label for="op_zamtel">
                <span class="op-icon op-zamtel">ZK</span>
                <span class="op-name">Zamtel Kwacha</span>
                <span class="op-sub">Zamtel</span>
              </label>
            </div>
          </div>

          {{-- Mobile money number --}}
          <div class="form-group">
            <label>Mobile Money Number *</label>
            <input type="tel" name="momo_phone" id="momoPhone"
              value="{{ old('momo_phone', $customer?->phone) }}"
              placeholder="e.g. 0977 000 000" required/>
          </div>

            <div class="momo-notice">
              <i class="fas fa-info-circle"></i>
              <span>After placing your order, you will receive a payment prompt on your phone. Enter your PIN to complete the payment.</span>
            </div>
          </div>{{-- /#momoSection --}}

          {{-- Card --}}
          <div id="cardSection" style="display:none">
            <div class="card-notice">
              <i class="fas fa-shield-alt"></i>
              <span>You will be taken to Lenco's secure card checkout to enter your Visa or Mastercard details. Your card number never touches our servers and 3-D Secure is supported.</span>
            </div>
            <p style="font-size:.78rem;color:#888;margin-top:12px">
              A valid email address is required for the card receipt.
            </p>
          </div>
        </div>

        <button type="submit" class="btn-pay" id="payButton" style="margin-top:20px">
          <i class="fas fa-lock"></i>
          <span id="payButtonLabel">Pay K{{ number_format($cart->total(),0) }} · Mobile Money</span>
        </button>
        <div class="linco-badge">
          <i class="fas fa-shield-alt"></i>
          Secured by Lenco · ZMW
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

@section('scripts')
<script>
(function () {
  const momo        = document.getElementById('momoSection');
  const card        = document.getElementById('cardSection');
  const email       = document.getElementById('emailField');
  const emailHint   = document.getElementById('emailHint');
  const momoPhone   = document.getElementById('momoPhone');
  const operators   = document.querySelectorAll('input[name="momo_operator"]');
  const buttonLabel = document.getElementById('payButtonLabel');
  const radios      = document.querySelectorAll('input[name="payment_method"]');

  function selectedMethod() {
    const checked = document.querySelector('input[name="payment_method"]:checked');
    return checked ? checked.value : 'mobile_money';
  }

  function applyMethod() {
    const isCard = selectedMethod() === 'card';

    momo.style.display = isCard ? 'none' : 'block';
    card.style.display = isCard ? 'block' : 'none';

    momoPhone.required = !isCard;
    momoPhone.disabled = isCard;
    operators.forEach(function (op) {
      op.required = !isCard;
      op.disabled = isCard;
    });

    email.required = isCard;
    emailHint.textContent = isCard ? '(Required for card)' : '(Optional)';

    buttonLabel.textContent = 'Pay K{{ number_format($cart->total(), 0) }} · ' + (isCard ? 'Card' : 'Mobile Money');
  }

  radios.forEach(function (radio) {
    radio.addEventListener('change', applyMethod);
  });

  applyMethod();
})();
</script>
@endsection
