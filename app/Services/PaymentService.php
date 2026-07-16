<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Wraps Moyasar's REST API (https://moyasar.com) — a Saudi payment gateway
 * supporting mada, Visa, Mastercard, and Apple Pay.
 *
 * To go live: sign up at moyasar.com, complete business verification, then set
 * MOYASAR_PUBLISHABLE_KEY and MOYASAR_SECRET_KEY in .env. Until both are set,
 * online payment stays disabled and the app only offers cash-on-delivery.
 */
class PaymentService
{
    private const BASE_URL = 'https://api.moyasar.com/v1';

    public function isConfigured(): bool
    {
        return filled(config('services.moyasar.publishable_key')) && filled(config('services.moyasar.secret_key'));
    }

    /**
     * Verify a payment's status directly with Moyasar (never trust the client-side
     * redirect alone — always confirm server-side with the secret key).
     */
    public function verify(string $paymentId): ?array
    {
        $secretKey = config('services.moyasar.secret_key');
        if (!$secretKey) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($secretKey, '')
                ->timeout(15)
                ->get(self::BASE_URL . "/payments/{$paymentId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Moyasar payment verification failed', ['payment_id' => $paymentId, 'status' => $response->status()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Moyasar payment verification error: ' . $e->getMessage());
            return null;
        }
    }
}
