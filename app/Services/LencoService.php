<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LencoService
{
    private string $apiKey;
    private string $publicKey;
    private string $webhookSecret;
    private string $baseUrl;
    private string $country;
    private string $currency;
    private string $bearer;

    public function __construct()
    {
        $this->apiKey        = (string) config('lenco.api_key', '');
        $this->publicKey     = (string) config('lenco.public_key', '');
        $this->webhookSecret = (string) config('lenco.webhook_secret', '');
        $this->baseUrl       = rtrim((string) config('lenco.base_url', 'https://api.lenco.co'), '/');
        $this->country       = (string) config('lenco.country', 'zm');
        $this->currency      = (string) config('lenco.currency', 'ZMW');
        $this->bearer        = (string) config('lenco.bearer', 'customer');
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    public function publicKey(): string
    {
        return $this->publicKey;
    }

    public function inlineUrl(): string
    {
        return (string) config('lenco.inline_url', 'https://pay.lenco.co/js/v1/inline.js');
    }

    /**
     * Initiate a mobile money collection request.
     * Lenco responds with status "pay-offline" while the customer authorises
     * the payment on their handset. We then rely on the webhook / status requery.
     */
    public function initiateMobileMoney(Order $order, string $phone, string $operator): array
    {
        $operator = strtolower($operator);

        $payload = [
            'amount'    => (float) $order->total,
            'reference' => $order->ref,
            'phone'     => $this->normalisePhone($phone),
            'operator'  => $operator,
            'country'   => $this->country,
            'bearer'    => $this->bearer,
        ];

        $response = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/access/v2/collections/mobile-money", $payload);

        if (!$response->successful() || $response->json('status') !== true) {
            Log::error('Lenco mobile money initiation failed', [
                'order'    => $order->ref,
                'status'   => $response->status(),
                'response' => $response->json(),
            ]);

            throw new \RuntimeException($response->json('message') ?? 'Payment gateway error.');
        }

        $data = (array) $response->json('data', []);

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'transaction_id'   => $data['id'] ?? null,
                'lenco_reference'  => $data['lencoReference'] ?? null,
                'payment_reference' => $order->ref,
                'gateway'          => 'lenco',
                'amount'           => $order->total,
                'fee'              => $data['fee'] ?? null,
                'currency'         => $data['currency'] ?? $this->currency,
                'status'           => 'pending',
                'payment_method'   => "mobile_money:{$operator}",
                'operator'         => $operator,
                'gateway_response' => $data,
                'paid_at'          => null,
            ]
        );

        return $data;
    }

    /**
     * Create (or reset) the local pending payment for a card checkout.
     * The actual card capture happens in Lenco's PCI-compliant inline widget.
     */
    public function prepareCardPayment(Order $order): Payment
    {
        $payment = Payment::firstOrNew(['order_id' => $order->id]);

        $payment->fill([
            'gateway'           => 'lenco',
            'amount'            => $order->total,
            'currency'          => $this->currency,
            'status'            => 'pending',
            'payment_method'    => 'card',
            'operator'          => null,
            'transaction_id'    => null,
            'lenco_reference'   => null,
            'payment_reference' => $payment->payment_reference ?: $order->ref,
            'gateway_response'  => null,
            'paid_at'           => null,
        ])->save();

        return $payment;
    }

    /**
     * Issue a fresh Lenco reference for another card attempt. Lenco rejects
     * duplicate references, so retries must never reuse the previous one.
     */
    public function rotateCardReference(Order $order): Payment
    {
        $payment = Payment::firstOrNew(['order_id' => $order->id]);

        $payment->fill([
            'gateway'           => 'lenco',
            'amount'            => $order->total,
            'currency'          => $this->currency,
            'status'            => 'pending',
            'payment_method'    => 'card',
            'operator'          => null,
            'transaction_id'    => null,
            'lenco_reference'   => null,
            'payment_reference' => $order->ref . '-R' . Str::upper(Str::random(8)),
            'gateway_response'  => null,
            'paid_at'           => null,
        ])->save();

        return $payment;
    }

    /**
     * Fetch a collection by our reference from Lenco.
     */
    public function getCollection(string $reference, bool $logFailure = true): array
    {
        $response = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/access/v2/collections/status/{$reference}");

        if (!$response->successful() || $response->json('status') !== true) {
            if ($logFailure) {
                Log::warning('Lenco status requery failed', [
                    'reference' => $reference,
                    'status'    => $response->status(),
                    'response'  => $response->json(),
                ]);
            }

            throw new \RuntimeException($response->json('message') ?? 'Could not verify payment status.');
        }

        return (array) $response->json('data', []);
    }

    /**
     * Poll transaction status by the order reference.
     */
    public function checkStatus(string $reference): string
    {
        return $this->getCollection($reference)['status'] ?? 'pending';
    }

    /**
     * Requery Lenco and sync the local order/payment record.
     * Returns the fresh collection data.
     */
    public function syncFromGateway(Order $order, bool $logFailure = true): array
    {
        $payment = Payment::firstOrNew(['order_id' => $order->id]);

        $data = $this->getCollection($payment->payment_reference ?: $order->ref, $logFailure);

        $payment->fill([
            'gateway'          => 'lenco',
            'amount'           => $payment->amount ?? $order->total,
            'currency'         => $data['currency'] ?? $this->currency,
            'transaction_id'   => $data['id'] ?? $payment->transaction_id,
            'lenco_reference'  => $data['lencoReference'] ?? $payment->lenco_reference,
            'fee'              => $data['fee'] ?? $payment->fee,
            'payment_method'   => $payment->payment_method ?? ($data['type'] ?? 'card'),
            'gateway_response' => array_merge($payment->gateway_response ?? [], $data),
        ]);

        $this->applyStatus($payment, $data['status'] ?? 'pending');

        return $data;
    }

    /**
     * Verify the HMAC SHA512 webhook signature from Lenco.
     * Lenco signs the raw body with SHA256(api_token) unless a hash key is configured.
     */
    public function verifyWebhookSignature(Request $request): bool
    {
        $signature = $request->header('X-Lenco-Signature');
        $key       = $this->webhookHashKey();

        if (!$signature || $key === '') {
            return false;
        }

        $expected = hash_hmac('sha512', $request->getContent(), $key);

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
        $data    = (array) ($payload['data'] ?? []);

        Log::info('Lenco webhook received', ['event' => $event, 'reference' => $data['reference'] ?? null]);

        if (in_array($event, ['collection.successful', 'collection.failed', 'collection.reversed'], true)) {
            $this->processCollectionEvent($event, $data);
        }
    }

    private function processCollectionEvent(string $event, array $data): void
    {
        $reference = $data['reference'] ?? null;
        if (!$reference) {
            return;
        }

        $order = Order::where('ref', $reference)
            ->orWhereHas('payment', function ($query) use ($reference) {
                $query->where('payment_reference', $reference);
            })
            ->first();

        if (!$order) {
            Log::warning('Lenco webhook: order not found', ['reference' => $reference]);
            return;
        }

        $payment = Payment::firstOrNew(['order_id' => $order->id]);

        if ($payment->payment_reference && $payment->payment_reference !== $reference) {
            Log::info('Lenco webhook: stale reference ignored', [
                'order'     => $order->ref,
                'reference' => $reference,
                'current'   => $payment->payment_reference,
            ]);
            return;
        }

        $payment->fill([
            'gateway'           => 'lenco',
            'amount'            => $payment->amount ?? $order->total,
            'currency'          => $data['currency'] ?? $this->currency,
            'transaction_id'    => $data['id'] ?? $payment->transaction_id,
            'lenco_reference'   => $data['lencoReference'] ?? $payment->lenco_reference,
            'payment_reference' => $payment->payment_reference ?: $reference,
            'fee'               => $data['fee'] ?? $payment->fee,
            'payment_method'    => $payment->payment_method ?? ($data['type'] ?? null),
            'gateway_response'  => array_merge($payment->gateway_response ?? [], $data),
        ]);

        if ($event === 'collection.successful') {
            $this->applyStatus($payment, 'successful');
        } elseif ($event === 'collection.reversed') {
            $this->applyStatus($payment, 'reversed');
        } else {
            $this->applyStatus($payment, 'failed');
        }
    }

    private function applyStatus(Payment $payment, string $status): void
    {
        if ($payment->status === 'refunded') {
            return;
        }

        if ($payment->status === 'completed' && !in_array($status, ['successful', 'reversed'], true)) {
            return;
        }

        if ($status === 'successful') {
            $payment->fill([
                'status'  => 'completed',
                'paid_at' => $payment->paid_at ?? now(),
            ]);
            $payment->save();
            $payment->order->update(['status' => 'paid']);

            Log::info('Lenco: payment completed', ['order' => $payment->order->ref]);
            return;
        }

        if ($status === 'reversed') {
            $payment->fill(['status' => 'refunded']);
            $payment->save();

            Log::warning('Lenco: payment reversed', ['order' => $payment->order->ref]);
            return;
        }

        if ($status === 'failed') {
            $payment->fill(['status' => 'failed']);
            $payment->save();

            Log::info('Lenco: payment failed', ['order' => $payment->order->ref]);
            return;
        }

        $payment->fill(['status' => 'pending']);
        $payment->save();
    }

    private function webhookHashKey(): string
    {
        if ($this->webhookSecret !== '') {
            return $this->webhookSecret;
        }

        return $this->apiKey !== '' ? hash('sha256', $this->apiKey) : '';
    }

    /**
     * Strip non-digits and return international format without a leading "+".
     */
    private function normalisePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '260' . substr($digits, 1);
        }

        if (strlen($digits) === 9) {
            $digits = '260' . $digits;
        }

        return $digits;
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
