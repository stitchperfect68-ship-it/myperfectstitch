@extends('layouts.app')
@section('title', 'Cart — My Perfect Stitch')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}"/>
@endpush

@section('content')
<div class="page-hero"><h1>Your <em>Cart</em></h1></div>

<section class="cart-section">
  <div class="cart-inner">
    @if($cart->isEmpty())
      <div class="empty-cart">
        <i class="fas fa-shopping-bag"></i>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added anything yet. Explore our collection!</p>
        <a href="{{ route('home') }}#shop" class="btn-red">Browse Products</a>
      </div>
    @else
      {{-- Remove forms live outside the update form to avoid illegal nesting --}}
      @foreach($cart->all() as $id => $item)
        <form id="remove-{{ $id }}" method="POST" action="{{ route('cart.remove', $id) }}">@csrf</form>
      @endforeach

      <form method="POST" action="{{ route('cart.update') }}">
        @csrf
        <table class="cart-table">
          <thead>
            <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
          </thead>
          <tbody>
            @foreach($cart->all() as $id => $item)
              <tr>
                <td class="td-product">
                  @if($item['image'])
                    <img class="cart-img" src="{{ str_starts_with($item['image'],'assets/') ? asset($item['image']) : \Illuminate\Support\Facades\Storage::disk('public')->url($item['image']) }}" alt="{{ $item['name'] }}"/>
                  @endif
                  <span style="font-weight:600">{{ $item['name'] }}</span>
                </td>
                <td data-label="Price">K{{ number_format($item['price'],0) }}</td>
                <td data-label="Qty"><input type="number" name="items[{{ $id }}]" value="{{ $item['quantity'] }}" min="1" class="qty-input"/></td>
                <td data-label="Subtotal" style="font-weight:700">K{{ number_format($item['price'] * $item['quantity'],0) }}</td>
                <td>
                  <button type="submit" form="remove-{{ $id }}" class="remove-btn"><i class="fas fa-trash-alt"></i></button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div style="text-align:right;margin-bottom:20px">
          <button type="submit" style="background:transparent;border:2px solid #100736;color:#100736;padding:8px 20px;border-radius:3px;font-family:var(--font-body);font-size:.8rem;font-weight:700;cursor:pointer">Update Cart</button>
        </div>
      </form>

      <div class="cart-summary-wrap">
        <div></div>
        <div class="cart-summary">
          <h3 style="font-size:1rem;font-weight:700;color:#100736;margin-bottom:16px">Order Summary</h3>
          <div class="cart-summary-row"><span>Subtotal</span><span>K{{ number_format($cart->total(),0) }}</span></div>
          <div class="cart-summary-row"><span>Shipping</span><span style="color:#888;font-style:italic">Calculated at checkout</span></div>
          <div class="cart-summary-row"><span>Total</span><span>K{{ number_format($cart->total(),0) }}</span></div>
          <button onclick="goToCheckout()" class="btn-checkout">Proceed to Checkout →</button>
          <a href="{{ route('home') }}#shop" class="btn-continue">← Continue Shopping</a>
        </div>
      </div>
    @endif
  </div>
</section>
@endsection
