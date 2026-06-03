<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\LencoService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService  $cart,
        private OrderService $orderService,
        private LencoService $lenco,
    ) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        $customer = auth('customer')->user();

        return view('checkout', ['cart' => $this->cart, 'customer' => $customer]);
    }

    public function process(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|string|max:30',
            'email'         => 'nullable|email|max:255',
            'street'        => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'notes'         => 'nullable|string|max:1000',
            'momo_phone'    => 'required|string|max:30',
            'momo_operator' => 'required|in:airtel,mtn,zamtel',
        ]);

        $validated['customer_id'] = auth('customer')->id();

        try {
            $order = $this->orderService->createFromCart($validated);
            $this->lenco->initiateMobileMoney($order, $validated['momo_phone'], $validated['momo_operator']);

            return redirect()->route('payment.pending', $order->ref);
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
