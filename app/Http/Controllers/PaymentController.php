<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\LencoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function pending(string $ref)
    {
        $order = Order::where('ref', $ref)->with('payment')->firstOrFail();
        return view('payment.pending', compact('order'));
    }

    public function status(string $ref, LencoService $lenco)
    {
        $order = Order::where('ref', $ref)->with('payment')->firstOrFail();

        // Already confirmed by webhook
        if (in_array($order->status, ['paid', 'processing', 'ready', 'dispatched', 'delivered'])) {
            return response()->json(['status' => 'successful', 'redirect' => route('orders.show', $ref)]);
        }

        try {
            $lStatus = $lenco->checkStatus($ref);
        } catch (\Throwable) {
            $lStatus = 'pending';
        }

        if ($lStatus === 'successful') {
            if ($order->payment) {
                $order->payment->update(['status' => 'completed', 'paid_at' => now()]);
            }
            $order->update(['status' => 'paid']);
            return response()->json(['status' => 'successful', 'redirect' => route('orders.show', $ref)]);
        }

        if (in_array($lStatus, ['failed', 'reversed', 'declined'])) {
            return response()->json(['status' => 'failed']);
        }

        return response()->json(['status' => 'pending']);
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
