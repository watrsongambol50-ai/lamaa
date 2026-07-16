<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * SMS gateway wrapper — implemented for Msegat (msegat.com), a Saudi SMS provider.
 *
 * To activate real SMS sending:
 *   1. Sign up at msegat.com and purchase an SMS package.
 *   2. Create an API key from your Msegat dashboard.
 *   3. In .env set: SMS_ENABLED=true, SMS_MSEGAT_USERNAME, SMS_MSEGAT_API_KEY,
 *      and SMS_MSEGAT_SENDER (your approved sender name).
 * Until SMS_ENABLED=true, this class only logs the message and returns false,
 * so the rest of the app keeps working normally without an SMS account.
 */
class SmsService
{
    public function send(string $phone, string $message): bool
    {
        if (!config('services.sms.enabled', false)) {
            Log::info("[SMS-DISABLED] Would send to {$phone}: {$message}");
            return false;
        }

        $username = config('services.sms.msegat.username');
        $apiKey   = config('services.sms.msegat.api_key');
        $sender   = config('services.sms.msegat.sender', 'LAMAA');

        if (!$username || !$apiKey) {
            Log::warning('SMS_ENABLED is true but Msegat credentials are missing — message not sent.');
            return false;
        }

        // Msegat expects local Saudi numbers without the leading "+" (e.g. 9665XXXXXXXX).
        $numbers = ltrim(preg_replace('/[^0-9]/', '', $phone), '0');
        if (!str_starts_with($numbers, '966')) {
            $numbers = '966' . $numbers;
        }

        try {
            $response = Http::asJson()->post('https://www.msegat.com/gw/sendsms.php', [
                'userName'  => $username,
                'apiKey'    => $apiKey,
                'numbers'   => $numbers,
                'userSender' => $sender,
                'msg'       => $message,
            ]);

            $body = $response->json();
            $success = $response->successful() && (($body['code'] ?? null) === 'M0000' || ($body['code'] ?? null) === 1);
            if (!$success) {
                Log::warning('Msegat SMS send failed', ['response' => $body]);
            }
            return $success;
        } catch (\Throwable $e) {
            Log::error('Msegat SMS send error: ' . $e->getMessage());
            return false;
        }
    }
}
