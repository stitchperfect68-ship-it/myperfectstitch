@extends('layouts.app')
@section('title', 'Signing you in… — My Perfect Stitch')

@push('styles')
<style>
.cb-page{min-height:80vh;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:20px;padding:120px 5% 60px}
.cb-spinner{width:44px;height:44px;border:3px solid rgba(249,176,64,.2);border-top-color:#F9B040;border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.cb-msg{font-family:var(--font-display);font-size:1rem;color:#100736;font-weight:600}
</style>
@endpush

@section('content')
<div class="cb-page">
  <div class="cb-spinner"></div>
  <p class="cb-msg">Signing you in…</p>
</div>
@endsection

@section('scripts')
<script>
// Supabase JS automatically detects the access_token in the URL hash from OAuth redirect.
// onAuthStateChange fires SIGNED_IN, _syncWithLaravel runs, then we redirect home.
document.addEventListener('sb:ready', () => {
  if (_sbSession?.user) {
    window.location.href = '{{ route("home") }}';
  }
});
// Fallback: if already resolved before event fires
setTimeout(() => {
  if (_sbSession?.user) window.location.href = '{{ route("home") }}';
  else if (!window.SUPABASE_URL?.includes('your-project-ref')) {
    // No session after 4s — something went wrong
    window.location.href = '{{ route("auth.login") }}';
  }
}, 4000);
</script>
@endsection
