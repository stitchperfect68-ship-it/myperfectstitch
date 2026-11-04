@extends('layouts.app')
@section('title', 'Card Payment — My Perfect Stitch')

@php
  $nameParts = preg_split('/\s+/', trim((string) $order->customer_name));
  $firstName = $nameParts[0] ?? '';
  $lastName  = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';
@endphp

@push('styles')
<style>
.pending-page{min-height:80vh;display:flex;align-items:center;justify-content:center;padding:120px 5% 60px;background:var(--cream)}
.pending-card{background:#fff;border-radius:8px;box-shadow:0 4px 24px rgba(0,0,0,.08);padding:48px 40px;max-width:480px;width:100%;text-align:center}
.pending-icon{width:72px;height:72px;border-radius:50%;background:#fffbf0;border:3px solid #F9B040;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:1.8rem;color:#F9B040}
.pending-icon.success{background:#d1fae5;border-color:#059669;color:#059669}
.pending-icon.failed{background:#fee2e2;border-color:#dc2626;color:#dc2626}
.pending-spinner{width:48px;height:48px;border:4px solid rgba(249,176,64,.2);border-top-color:#F9B040;border-radius:50%;animation:pspin .8s linear infinite;margin:0 auto 20px}
@keyframes pspin{to{transform:rotate(360deg)}}
.pending-title{font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:#100736;margin-bottom:8px}
.pending-sub{font-size:.88rem;color:#666;line-height:1.6;margin-bottom:24px}
.pending-ref{display:inline-block;background:#f9f5ff;border:1px solid #e8e4f0;padding:6px 16px;border-radius:20px;font-size:.8rem;font-weight:700;color:#100736;margin-bottom:24px}
.pending-steps{text-align:left;background:#f9f7ff;border-radius:6px;padding:16px 20px;margin-bottom:24px}
.pending-step{display:flex;align-items:flex-start;gap:12px;padding:8px 0;border-bottom:1px solid #f0eef8;font-size:.82rem;color:#555}
.pending-step:last-child{border-bottom:none}
.pending-step-num{width:22px;height:22px;border-radius:50%;background:#F9B040;color:#100736;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px}
.btn-retry{display:inline-block;background:#F9B040;color:#100736;padding:11px 28px;border-radius:4px;font-family:var(--font-body);font-size:.88rem;font-weight:700;text-decoration:none;border:none;cursor:pointer}
.btn-secondary{display:inline-block;margin-top:14px;font-size:.82rem;color:#888;text-decoration:underline}
@media(max-width:480px){.pending-card{padding:32px 20px}}
</style>
@endpush

@section('content')
<div class="pending-page">
  <div class="pending-card">

    {{-- Loading --}}
    <div id="stateLoading">
      <div class="pending-spinner"></div>
      <h1 class="pending-title">Opening Secure Card Checkout</h1>
      <p class="pending-sub">Connecting you to Lenco's PCI-compliant payment page. Please do not close this window.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
    </div>

    {{-- Awaiting confirmation --}}
    <div id="statePending" style="display:none">
      <div class="pending-spinner"></div>
      <h1 class="pending-title">Confirming Your Payment</h1>
      <p class="pending-sub">We are waiting for Lenco to confirm your card payment. This usually takes a few seconds.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
    </div>

    {{-- Success --}}
    <div id="stateSuccess" style="display:none">
      <div class="pending-icon success"><i class="fas fa-check"></i></div>
      <h1 class="pending-title">Payment Successful!</h1>
      <p class="pending-sub">Your card payment has been confirmed. Your order is now being processed.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
      <p style="font-size:.82rem;color:#888">Redirecting to your order…</p>
    </div>

    {{-- Failed --}}
    <div id="stateFailed" style="display:none">
      <div class="pending-icon failed"><i class="fas fa-times"></i></div>
      <h1 class="pending-title">Payment Failed</h1>
      <p class="pending-sub" id="failedMessage">The card payment was not completed. Please check your card details and try again.</p>
      <button type="button" class="btn-retry" onclick="retryCard()">Try Again</button>
      <br>
      <a href="{{ route('orders.show', $order->ref) }}" class="btn-secondary">View order status</a>
    </div>

    {{-- Closed by customer --}}
    <div id="stateClosed" style="display:none">
      <div class="pending-icon"><i class="fas fa-credit-card"></i></div>
      <h1 class="pending-title">Card Payment Cancelled</h1>
      <p class="pending-sub">You closed the secure card checkout before completing the payment. Your order is still waiting for payment.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
      <button type="button" class="btn-retry" onclick="retryCard()">Pay with Card</button>
      <br>
      <a href="{{ route('orders.show', $order->ref) }}" class="btn-secondary">View order status</a>
    </div>

    {{-- Timeout --}}
    <div id="stateTimeout" style="display:none">
      <div class="pending-icon"><i class="fas fa-clock"></i></div>
      <h1 class="pending-title">Still Waiting for Confirmation</h1>
      <p class="pending-sub">We have not received confirmation yet. If you completed the payment, it will still be processed and your order updated shortly.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
      <a href="{{ route('orders.show', $order->ref) }}" class="btn-retry">Check Order Status</a>
    </div>

    {{-- Widget failed to load --}}
    <div id="stateError" style="display:none">
      <div class="pending-icon failed"><i class="fas fa-exclamation-triangle"></i></div>
      <h1 class="pending-title">Could Not Open Card Checkout</h1>
      <p class="pending-sub" id="errorMessage">The secure card checkout could not be loaded. Please check your connection and try again.</p>
      <button type="button" class="btn-retry" onclick="retryCard()">Try Again</button>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script src="{{ $inlineUrl }}"></script>
<script>
(function () {
  const VERIFY_URL = '{{ route("payment.verify", $order->ref) }}';
  const STATUS_URL = '{{ route("payment.status", $order->ref) }}';
  const ORDER_URL  = '{{ route("orders.show", $order->ref) }}';
  const CSRF       = '{{ csrf_token() }}';

  let launched = false;
  let verifying = false;
  let pollTimer = null;
  let pollAttempts = 0;

  function show(state) {
    ['Loading', 'Pending', 'Success', 'Failed', 'Closed', 'Timeout', 'Error'].forEach(function (name) {
      const el = document.getElementById('state' + name);
      if (el) el.style.display = name === state ? 'block' : 'none';
    });
  }

  function stopPolling() {
    if (pollTimer) {
      clearInterval(pollTimer);
      pollTimer = null;
    }
  }

  async function verifyPayment() {
    verifying = true;
    stopPolling();
    show('Pending');

    try {
      const response = await fetch(VERIFY_URL, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF
        },
        body: '{}'
      });
      const data = await response.json();

      if (data.status === 'successful') {
        show('Success');
        setTimeout(function () { window.location.href = data.redirect || ORDER_URL; }, 1500);
        return;
      }

      if (data.status === 'failed') {
        document.getElementById('failedMessage').textContent =
          data.message || 'The card payment was not completed. Please try again.';
        show('Failed');
        return;
      }
    } catch (e) {
      // fall through to polling
    } finally {
      verifying = false;
    }

    startPolling();
  }

  function startPolling() {
    stopPolling();
    pollAttempts = 0;
    show('Pending');

    pollTimer = setInterval(async function () {
      pollAttempts++;

      try {
        const response = await fetch(STATUS_URL, { headers: { 'Accept': 'application/json' } });
        const data = await response.json();

        if (data.status === 'successful') {
          stopPolling();
          show('Success');
          setTimeout(function () { window.location.href = data.redirect || ORDER_URL; }, 1500);
          return;
        }

        if (data.status === 'failed') {
          stopPolling();
          document.getElementById('failedMessage').textContent =
            data.message || 'The card payment was not completed. Please try again.';
          show('Failed');
          return;
        }
      } catch (e) {
        // keep polling
      }

      if (pollAttempts >= 24) {
        stopPolling();
        show('Timeout');
      }
    }, 5000);
  }

  function launch() {
    if (launched) return;
    launched = true;

    if (!window.LencoPay) {
      launched = false;
      document.getElementById('errorMessage').textContent =
        'The secure card checkout could not be loaded. Please check your connection and try again.';
      show('Error');
      return;
    }

    show('Loading');

    window.LencoPay.getPaid({
      key: @json($publicKey),
      email: @json($order->customer_email),
      reference: @json($reference),
      amount: {{ (float) $order->total }},
      currency: @json(config('lenco.currency', 'ZMW')),
      label: @json('Order ' . $order->ref),
      channels: ['card'],
      customer: {
        firstName: @json($firstName),
        lastName: @json($lastName),
        phone: @json($order->customer_phone)
      },
      billing: {
        streetAddress: @json($order->shipping_street),
        city: @json($order->shipping_city),
        country: @json(strtoupper(config('lenco.country', 'zm')))
      },
      onSuccess: function () {
        verifyPayment();
      },
      onClose: function () {
        launched = false;
        if (verifying) return;
        if (document.getElementById('stateSuccess').style.display !== 'block') {
          show('Closed');
        }
      },
      onConfirmationPending: function () {
        startPolling();
      }
    });
  }

  window.retryCard = function () {
    stopPolling();
    window.location.href = '{{ route("payment.card", $order->ref) }}?retry=1';
  };

  if (@json($resume ?? false)) {
    startPolling();
  } else {
    launch();
  }
})();
</script>
@endsection
