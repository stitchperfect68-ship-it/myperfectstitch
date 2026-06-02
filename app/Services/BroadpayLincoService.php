<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BroadpayLincoService
{
    private string $merchantId;
    private string $apiKey;
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('broadpay.merchant_id');
        $this->apiKey     = config('broadpay.api_key');
        $this->secretKey  = config('broadpay.secret_key');
        $this->baseUrl    = rtrim(config('broadpay.base_url'), '/');
    }

    /**
     * Initiate a payment for an order. Returns ['url' => ..., 'ref' => ...] on success.
     */
    public function initiatePayment(Order $order): array
    {
        $payload = [
            'merchant_id'   => $this->merchantId,
            'reference'     => $order->ref,
            'amount'        => (string) number_format($order->total, 2, '.', ''),
            'currency'      => config('broadpay.currency', 'ZMW'),
            'description'   => "Order {$order->ref} — My Perfect Stitch",
            'customer_name' => $order->customer_name,
            'customer_phone'=> $order->customer_phone,
            'customer_email'=> $order->customer_email ?? '',
            'callback_url'  => config('broadpay.callback_url'),
            'webhook_url'   => config('broadpay.webhook_url'),
            'return_url'    => route('orders.show', $order->ref),
        ];

        $payload['signature'] = $this->generateSignature($payload);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept'        => 'application/json',
        ])->post("{$this->baseUrl}/payments/initiate", $payload);

        if ($response->successful()) {
            $data = $response->json();

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'transaction_id' => $data['transaction_id'] ?? null,
                    'gateway'        => 'broadpay_linco',
                    'amount'         => $order->total,
                    'currency'       => 'ZMW',
                    'status'         => 'pending',
                    'payment_url'    => $data['payment_url'] ?? null,
                    'gateway_response' => $data,
                ]
            );

            return [
                'url' => $data['payment_url'],
                'ref' => $data['transaction_id'] ?? $order->ref,
            ];
        }

        Log::error('Broadpay payment initiation failed', [
            'order'    => $order->ref,
            'response' => $response->json(),
        ]);

        throw new \RuntimeException('Payment gateway error: ' . ($response->json('message') ?? 'Unknown error'));
    }

    /**
     * Verify payment status with the gateway.
     */
    public function verifyPayment(string $transactionId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept'        => 'application/json',
        ])->get("{$this->baseUrl}/payments/{$transactionId}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \RuntimeException('Could not verify payment status.');
    }

    /**
     * Handle incoming webhook from Broadpay.
     */
    public function handleWebhook(array $payload): void
    {
        $signature = $payload['signature'] ?? '';
        unset($payload['signature']);

        if (!$this->verifySignature($payload, $signature)) {
            Log::warning('Broadpay webhook signature mismatch', $payload);
            return;
        }

        $payment = Payment::where('transaction_id', $payload['transaction_id'] ?? '')
            ->orWhereHas('order', fn ($q) => $q->where('ref', $payload['reference'] ?? ''))
            ->first();

        if (!$payment) {
            Log::warning('Broadpay webhook: payment not found', $payload);
            return;
        }

        $status = strtolower($payload['status'] ?? '');

        $payment->update([
            'status'           => in_array($status, ['success', 'completed']) ? 'completed' : 'failed',
            'gateway_response' => $payload,
            'paid_at'          => in_array($status, ['success', 'completed']) ? now() : null,
        ]);

        if (in_array($status, ['success', 'completed'])) {
            $payment->order->update(['status' => 'paid']);
        }
    }

    /**
     * Generate HMAC-SHA256 signature for a payload.
     */
    public function generateSignature(array $data): string
    {
        ksort($data);
        $queryString = http_build_query($data);
        return hash_hmac('sha256', $queryString, $this->secretKey);
    }

    private function verifySignature(array $data, string $signature): bool
    {
        return hash_equals($this->generateSignature($data), $signature);
    }
}
