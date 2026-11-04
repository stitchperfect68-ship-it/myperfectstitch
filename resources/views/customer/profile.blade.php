@extends('layouts.app')

@section('title', 'My Profile - My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
@endpush

@section('content')
<div class="acct-hero">
  <h1>My <em>Profile</em></h1>
  <p>Manage your account details and password.</p>
</div>

<section class="acct-page">
  <div class="acct-container">
    @if (session('success'))
      <div class="acct-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if (isset($errors) && $errors->any())
      <div class="acct-alert error">
        <i class="fas fa-exclamation-circle"></i>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="acct-layout">
      @include('customer.partials.sidebar', ['active' => 'profile'])

      <div class="acct-main">
        <div class="acct-card">
          <div class="acct-head">
            <div>
              <h2>Account Details</h2>
              <p>Member since {{ $customer->created_at->format('M Y') }}</p>
            </div>
          </div>

          <form method="POST" action="{{ route('customer.profile.update') }}" class="acct-form">
            @csrf
            @method('PUT')

            <div class="acct-form-row">
              <div class="acct-field">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required/>
              </div>
              <div class="acct-field">
                <label for="phone">Phone / WhatsApp</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"/>
              </div>
            </div>

            <div class="acct-field">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}" placeholder="you@example.com"/>
            </div>

            <div class="acct-form-row">
              <div class="acct-field">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Leave blank to keep current" autocomplete="new-password"/>
              </div>
              <div class="acct-field">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat new password" autocomplete="new-password"/>
              </div>
            </div>

            <button type="submit"><i class="fas fa-save"></i> Save Changes</button>
          </form>
        </div>

        <div class="acct-card">
          <div class="acct-head">
            <h2>Account</h2>
          </div>
          <p class="acct-muted" style="margin-bottom:16px">Signing out will end your session on this device.</p>
          <form method="POST" action="{{ route('customer.logout') }}">
            @csrf
            <button type="submit" class="acct-order-link" style="background:#fff;cursor:pointer;font-family:var(--font-body)">
              <i class="fas fa-sign-out-alt"></i> Sign Out
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
