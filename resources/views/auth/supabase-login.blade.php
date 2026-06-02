@extends('layouts.app')
@section('title', 'Sign In — My Perfect Stitch')

@push('styles')
<style>
.auth-page { min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 120px 5% 60px; background: #f9f5f0; }
.auth-card { background: #fff; border-radius: 8px; box-shadow: 0 8px 40px rgba(0,0,0,0.12); padding: 48px 40px; width: 100%; max-width: 440px; }
.auth-card h1 { font-family: var(--font-display); font-size: 1.8rem; color: #100736; font-weight: 700; margin-bottom: 6px; }
.auth-card p { color: #666; font-size: 0.9rem; margin-bottom: 32px; }
</style>
@endpush

@section('content')
<div class="auth-page">
  <div class="auth-card">
    <h1>Sign In</h1>
    <p>Sign in or create an account to place orders and submit quote requests.</p>
    <div id="supabase-auth-ui">
      <p style="color:#888;font-size:.85rem;text-align:center;">Loading sign-in form…</p>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// The Supabase auth modal will open automatically on this page
document.addEventListener('DOMContentLoaded', () => {
  if (window._sbReady) { openAuthModal(); }
  else { document.addEventListener('sb:ready', openAuthModal); }
});
</script>
@endsection
