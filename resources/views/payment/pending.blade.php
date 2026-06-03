@extends('layouts.app')
@section('title', 'Awaiting Payment — My Perfect Stitch')

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
.pending-timer{font-size:.78rem;color:#888;margin-bottom:16px}
.pending-timer span{font-weight:700;color:#100736}
.btn-cancel{background:none;border:1px solid #e0dce8;color:#888;padding:9px 20px;border-radius:4px;font-family:var(--font-body);font-size:.82rem;cursor:pointer;transition:border-color .2s}
.btn-cancel:hover{border-color:#100736;color:#100736}
.btn-retry{display:inline-block;background:#F9B040;color:#100736;padding:11px 28px;border-radius:4px;font-family:var(--font-body);font-size:.88rem;font-weight:700;text-decoration:none;margin-top:16px}
@media(max-width:480px){.pending-card{padding:32px 20px}}
</style>
@endpush

@section('content')
<div class="pending-page">
  <div class="pending-card" id="pendingCard">

    {{-- Waiting state --}}
    <div id="stateWaiting">
      <div class="pending-spinner"></div>
      <h1 class="pending-title">Awaiting Your Authorization</h1>
      <p class="pending-sub">A payment prompt has been sent to your mobile money number. Please check your phone and enter your PIN to complete the payment.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
      <div class="pending-steps">
        <div class="pending-step"><span class="pending-step-num">1</span><span>Open your mobile money app or check for a USSD prompt on your phone.</span></div>
        <div class="pending-step"><span class="pending-step-num">2</span><span>Confirm the payment of <strong>K{{ number_format($order->total, 0) }}</strong> to My Perfect Stitch.</span></div>
        <div class="pending-step"><span class="pending-step-num">3</span><span>Enter your mobile money PIN to authorise.</span></div>
      </div>
      <div class="pending-timer">This page refreshes automatically &mdash; time remaining: <span id="countdown">2:00</span></div>
      <button class="btn-cancel" onclick="cancelPending()">Cancel &amp; Return to Checkout</button>
    </div>

    {{-- Success state --}}
    <div id="stateSuccess" style="display:none">
      <div class="pending-icon success"><i class="fas fa-check"></i></div>
      <h1 class="pending-title">Payment Successful!</h1>
      <p class="pending-sub">Your payment has been confirmed. Your order is now being processed.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
      <p style="font-size:.82rem;color:#888">Redirecting to your order…</p>
    </div>

    {{-- Failed state --}}
    <div id="stateFailed" style="display:none">
      <div class="pending-icon failed"><i class="fas fa-times"></i></div>
      <h1 class="pending-title">Payment Failed</h1>
      <p class="pending-sub">The payment was not completed. This could be due to insufficient funds, an incorrect PIN, or a timeout. Please try again.</p>
      <a href="{{ route('checkout.index') }}" class="btn-retry">Try Again</a>
    </div>

    {{-- Timeout state --}}
    <div id="stateTimeout" style="display:none">
      <div class="pending-icon"><i class="fas fa-clock"></i></div>
      <h1 class="pending-title">Payment Timed Out</h1>
      <p class="pending-sub">We did not receive confirmation within 2 minutes. If you completed the payment on your phone, it will still be processed and your order updated shortly.</p>
      <div class="pending-ref">{{ $order->ref }}</div>
      <a href="{{ route('orders.show', $order->ref) }}" class="btn-retry" style="display:block;text-align:center;margin-bottom:10px">Check Order Status</a>
      <a href="{{ route('checkout.index') }}" style="font-size:.82rem;color:#888">Return to checkout</a>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
const STATUS_URL = '{{ route("payment.status", $order->ref) }}';
const ORDER_URL  = '{{ route("orders.show", $order->ref) }}';
let elapsed = 0;
const TIMEOUT = 120;

function show(state) {
  ['Waiting','Success','Failed','Timeout'].forEach(s =>
    document.getElementById('state' + s).style.display = s === state ? 'block' : 'none'
  );
}

function updateCountdown() {
  const remaining = TIMEOUT - elapsed;
  const m = Math.floor(remaining / 60);
  const s = remaining % 60;
  const el = document.getElementById('countdown');
  if (el) el.textContent = m + ':' + String(s).padStart(2, '0');
}

async function poll() {
  try {
    const r = await fetch(STATUS_URL, { headers: { 'Accept': 'application/json' } });
    const data = await r.json();

    if (data.status === 'successful') {
      show('Success');
      setTimeout(() => window.location.href = data.redirect || ORDER_URL, 2000);
      return;
    }
    if (data.status === 'failed') {
      show('Failed');
      return;
    }
  } catch(e) {}

  elapsed += 5;
  updateCountdown();

  if (elapsed >= TIMEOUT) {
    show('Timeout');
    return;
  }

  setTimeout(poll, 5000);
}

function cancelPending() {
  window.location.href = '{{ route("checkout.index") }}';
}

updateCountdown();
setTimeout(poll, 5000);
</script>
@endsection
