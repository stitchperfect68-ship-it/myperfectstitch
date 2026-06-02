<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\BroadpayLincoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function callback(Request $request)
    {
        $status = $request->input('status', 'failed');
        $ref    = $request->input('reference') ?? $request->input('ref');

        $order = Order::where('ref', $ref)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        if (in_array($status, ['success', 'completed'])) {
            return redirect()->route('orders.show', $order->ref)
                ->with('success', 'Payment successful! Your order has been confirmed.');
        }

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was not completed. Please try again.');
    }

    public function webhook(Request $request, BroadpayLincoService $linco)
    {
        try {
            $linco->handleWebhook($request->all());
        } catch (\Throwable $e) {
            Log::error('Broadpay webhook error: ' . $e->getMessage(), $request->all());
        }

        return response()->json(['status' => 'ok']);
    }
}
