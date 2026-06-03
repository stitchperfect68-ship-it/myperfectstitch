<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LencoService
{
    private string $apiKey;
    private string $webhookSecret;
    private string $baseUrl;
    private string $country;

    public function __construct()
    {
        $this->apiKey        = config('lenco.api_key');
        $this->webhookSecret = config('lenco.webhook_secret');
        $this->baseUrl       = rtrim(config('lenco.base_url'), '/');
        $this->country       = config('lenco.country', 'zm');
    }

    /**
     * Initiate a mobile money collection request.
     * Returns ['transaction_id' => ..., 'status' => 'pay-offline'] on success.
     */
    public function initiateMobileMoney(Order $order, string $phone, string $operator): array
    {
        $payload = [
            'amount'    => (float) $order->total,
            'reference' => $order->ref,
            'phone'     => $this->normalisePhone($phone),
            'operator'  => strtolower($operator),
            'country'   => $this->country,
            'bearer'    => 'customer',
        ];

        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/access/v2/collections/mobile-money", $payload);

        if ($response->successful() && $response->json('status') === true) {
            $data = $response->json('data');

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'transaction_id'   => $data['id'] ?? null,
                    'gateway'          => 'lenco',
                    'amount'           => $order->total,
                    'currency'         => 'ZMW',
                    'status'           => 'pending',
                    'payment_method'   => "mobile_money:{$operator}",
                    'gateway_response' => $data,
                ]
            );

            return $data;
        }

        $error = $response->json('message') ?? 'Payment gateway error.';
        Log::error('Lenco mobile money initiation failed', [
            'order'    => $order->ref,
            'response' => $response->json(),
        ]);

        throw new \RuntimeException($error);
    }

    /**
     * Poll transaction status by the order reference.
     */
    public function checkStatus(string $reference): string
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/access/v2/collections/mobile-money/by-reference/{$reference}");

        if ($response->successful() && $response->json('status') === true) {
            return $response->json('data.status', 'pending');
        }

        return 'pending';
    }

    /**
     * Verify the HMAC SHA512 webhook signature from Lenco.
     */
    public function verifyWebhookSignature(Request $request): bool
    {
        $signature = $request->header('X-Lenco-Signature');
        if (!$signature || !$this->webhookSecret) {
            return false;
        }

        $expected = hash_hmac('sha512', $request->getContent(), $this->webhookSecret);
        return hash_equals($expected, $signature);
    }

    /**
     * Process an incoming webhook payload.
     */
    public function handleWebhook(Request $request): void
    {
        if (!$this->verifyWebhookSignature($request)) {
            Log::warning('Lenco webhook: invalid signature');
            return;
        }

        $payload = $request->json()->all();
        $event   = $payload['event'] ?? '';
        $data    = $payload['data'] ?? [];

        Log::info('Lenco webhook received', ['event' => $event, 'reference' => $data['reference'] ?? null]);

        // Handle mobile money collection events
        if (in_array($event, ['collection.successful', 'collection.failed', 'collection.reversed'])) {
            $this->processCollectionEvent($event, $data);
        }
    }

    private function processCollectionEvent(string $event, array $data): void
    {
        $reference = $data['reference'] ?? null;
        if (!$reference) return;

        $order = Order::where('ref', $reference)->first();
        if (!$order) {
            Log::warning('Lenco webhook: order not found', ['reference' => $reference]);
            return;
        }

        $payment = Payment::where('order_id', $order->id)->first();
        if (!$payment) return;

        if ($event === 'collection.successful') {
            $payment->update([
                'status'           => 'completed',
                'transaction_id'   => $data['id'] ?? $payment->transaction_id,
                'paid_at'          => now(),
                'gateway_response' => $data,
            ]);
            $order->update(['status' => 'paid']);
            Log::info('Lenco: payment completed', ['order' => $reference]);
        } elseif (in_array($event, ['collection.failed', 'collection.reversed'])) {
            $payment->update([
                'status'           => 'failed',
                'gateway_response' => $data,
            ]);
            Log::info('Lenco: payment failed/reversed', ['order' => $reference, 'event' => $event]);
        }
    }

    /**
     * Strip non-digits and ensure Zambia country code.
     */
    private function normalisePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        // If starts with 0, replace with 260 (Zambia code)
        if (str_starts_with($digits, '0')) {
            $digits = '260' . substr($digits, 1);
        }

        // If no country code, assume Zambia
        if (strlen($digits) === 9) {
            $digits = '260' . $digits;
        }

        return '+' . $digits;
    }

    private function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
    }
}
