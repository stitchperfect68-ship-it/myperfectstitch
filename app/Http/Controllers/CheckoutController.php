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

        return view('checkout', [
            'cart'        => $this->cart,
            'customer'    => $customer,
            'cardEnabled' => (bool) config('lenco.public_key'),
        ]);
    }

    public function process(Request $request)
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('home')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:30',
            'email'          => 'required_if:payment_method,card|nullable|email|max:255',
            'street'         => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
            'payment_method' => 'required|in:mobile_money,card',
            'momo_phone'     => 'required_if:payment_method,mobile_money|nullable|string|max:30',
            'momo_operator'  => 'required_if:payment_method,mobile_money|nullable|in:airtel,mtn,zamtel',
        ]);

        if ($validated['payment_method'] === 'card' && !config('lenco.public_key')) {
            return back()->withInput()->with('error', 'Card payments are not available yet. Please use mobile money.');
        }

        $validated['customer_id'] = auth('customer')->id();

        try {
            $order = $this->orderService->createFromCart($validated);
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        if ($validated['payment_method'] === 'card') {
            $this->lenco->prepareCardPayment($order);

            return redirect()->route('payment.card', $order->ref);
        }

        try {
            $this->lenco->initiateMobileMoney($order, $validated['momo_phone'], $validated['momo_operator']);
        } catch (\RuntimeException $e) {
            \App\Models\Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'gateway'        => 'lenco',
                    'amount'         => $order->total,
                    'currency'       => config('lenco.currency', 'ZMW'),
                    'status'         => 'failed',
                    'payment_method' => 'mobile_money:' . $validated['momo_operator'],
                    'operator'       => $validated['momo_operator'],
                ]
            );

            return redirect()->route('orders.show', $order->ref)
                ->with('error', $e->getMessage());
        }

        return redirect()->route('payment.pending', $order->ref);
    }
}
