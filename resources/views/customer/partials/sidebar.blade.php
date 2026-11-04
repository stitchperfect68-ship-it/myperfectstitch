@php
  $active = $active ?? '';
  $nameParts = explode(' ', trim($customer->name ?? 'Customer'));
  $initials = strtoupper(substr($nameParts[0] ?? 'C', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
@endphp

<aside class="acct-sidebar">
  <div class="acct-user">
    <div class="acct-avatar">{{ $initials }}</div>
    <div class="acct-user-name">{{ $customer->name }}</div>
    <div class="acct-user-email">{{ $customer->email ?: $customer->phone }}</div>
  </div>

  <nav class="acct-nav">
    <a href="{{ route('customer.orders') }}" class="{{ $active === 'orders' ? 'active' : '' }}">
      <i class="fas fa-box"></i> My Orders
    </a>
    <a href="{{ route('customer.profile') }}" class="{{ $active === 'profile' ? 'active' : '' }}">
      <i class="fas fa-user"></i> Profile
    </a>
    <a href="{{ route('cart.index') }}">
      <i class="fas fa-shopping-bag"></i> My Cart
    </a>
    <a href="{{ route('shop.index') }}">
      <i class="fas fa-store"></i> Continue Shopping
    </a>
    <form method="POST" action="{{ route('customer.logout') }}">
      @csrf
      <button type="submit" class="acct-logout"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
    </form>
  </nav>
</aside>
