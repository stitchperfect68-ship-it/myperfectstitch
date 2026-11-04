<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\LencoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private const PAID_STATUSES = ['paid', 'processing', 'ready', 'dispatched', 'delivered'];

    public function pending(string $ref)
    {
        $order = Order::where('ref', $ref)->with('payment')->firstOrFail();

        return view('payment.pending', compact('order'));
    }

    public function card(Request $request, string $ref, LencoService $lenco)
    {
        $order = Order::where('ref', $ref)->with('payment')->firstOrFail();

        if (in_array($order->status, self::PAID_STATUSES, true)) {
            return redirect()->route('orders.show', $ref);
        }

        if (!$lenco->publicKey()) {
            return redirect()->route('orders.show', $ref)
                ->with('error', 'Card payments are not configured yet. Please contact us to complete this order.');
        }

        if (!$order->customer_email) {
            return redirect()->route('orders.show', $ref)
                ->with('error', 'An email address is required for card payments.');
        }

        $payment = $order->payment;

        if ($payment && $payment->status === 'completed') {
            return redirect()->route('orders.show', $ref);
        }

        if ($payment && $payment->payment_method !== 'card' && $payment->status !== 'failed') {
            return redirect()->route('payment.pending', $ref);
        }

        if ($request->boolean('retry') || ($payment && ($payment->payment_method !== 'card' || $payment->status === 'failed'))) {
            $payment = $lenco->rotateCardReference($order);
        } elseif (!$payment) {
            $payment = $lenco->prepareCardPayment($order);
        } elseif (!$payment->payment_reference) {
            $payment->update(['payment_reference' => $order->ref]);
        }

        $resume = false;

        if (!$request->boolean('retry') && $payment->status === 'pending') {
            try {
                $data = $lenco->syncFromGateway($order, false);

                if (($data['status'] ?? null) === 'successful') {
                    return redirect()->route('orders.show', $ref);
                }

                if (($data['status'] ?? null) === 'failed') {
                    $payment = $lenco->rotateCardReference($order);
                } else {
                    $resume = true;
                }
            } catch (\Throwable) {
                // No collection exists yet — safe to launch the widget.
            }
        }

        return view('payment.card', [
            'order'     => $order,
            'payment'   => $payment,
            'reference' => $payment->payment_reference,
            'resume'    => $resume,
            'publicKey' => $lenco->publicKey(),
            'inlineUrl' => $lenco->inlineUrl(),
        ]);
    }

    /**
     * Called by the inline widget (and the redirect callback) after the
     * customer completes card authorisation. Always requery Lenco — never
     * trust the client.
     */
    public function verify(string $ref, LencoService $lenco)
    {
        $order = Order::where('ref', $ref)->with('payment')->firstOrFail();

        if (in_array($order->status, self::PAID_STATUSES, true)) {
            return response()->json([
                'status'   => 'successful',
                'redirect' => route('orders.show', $ref),
            ]);
        }

        try {
            $data = $lenco->syncFromGateway($order);
        } catch (\Throwable $e) {
            Log::warning('Lenco verify failed', ['order' => $ref, 'error' => $e->getMessage()]);

            return response()->json(['status' => 'pending', 'message' => $e->getMessage()]);
        }

        $status = $data['status'] ?? 'pending';

        if ($status === 'successful') {
            return response()->json([
                'status'   => 'successful',
                'redirect' => route('orders.show', $ref),
            ]);
        }

        if (in_array($status, ['failed', 'reversed'], true)) {
            return response()->json([
                'status'  => 'failed',
                'message' => $data['reasonForFailure'] ?? 'The payment was not completed.',
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

    public function status(string $ref, LencoService $lenco)
    {
        $order = Order::where('ref', $ref)->with('payment')->firstOrFail();

        if (in_array($order->status, self::PAID_STATUSES, true)) {
            return response()->json(['status' => 'successful', 'redirect' => route('orders.show', $ref)]);
        }

        try {
            $data = $lenco->syncFromGateway($order);
        } catch (\Throwable) {
            return response()->json(['status' => 'pending']);
        }

        $status = $data['status'] ?? 'pending';

        if ($status === 'successful') {
            return response()->json(['status' => 'successful', 'redirect' => route('orders.show', $ref)]);
        }

        if (in_array($status, ['failed', 'reversed'], true)) {
            return response()->json([
                'status'  => 'failed',
                'message' => $data['reasonForFailure'] ?? null,
            ]);
        }

        return response()->json(['status' => 'pending']);
    }

    /**
     * Optional browser redirect target if the widget is configured with a
     * callback URL instead of the JS onSuccess handler.
     */
    public function callback(Request $request, LencoService $lenco)
    {
        $ref = $request->query('reference') ?? $request->input('reference');

        if (!$ref) {
            return redirect()->route('home')->with('error', 'Missing payment reference.');
        }

        $order = Order::where('ref', $ref)
            ->orWhereHas('payment', function ($query) use ($ref) {
                $query->where('payment_reference', $ref);
            })
            ->with('payment')
            ->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        $failureRoute = $order->payment?->payment_method === 'card'
            ? 'payment.card'
            : 'payment.pending';

        try {
            $data = $lenco->syncFromGateway($order);
        } catch (\Throwable $e) {
            return redirect()->route($failureRoute, $ref)->with('error', $e->getMessage());
        }

        if (($data['status'] ?? null) === 'successful') {
            return redirect()->route('orders.show', $ref)->with('success', 'Payment received. Thank you!');
        }

        return redirect()->route($failureRoute, $ref)
            ->with('error', $data['reasonForFailure'] ?? 'Payment was not completed.');
    }

    public function webhook(Request $request, LencoService $lenco)
    {
        try {
            $lenco->handleWebhook($request);
        } catch (\Throwable $e) {
            Log::error('Lenco webhook error: ' . $e->getMessage());
        }

        return response()->json(['status' => 'ok']);
    }
}
