<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlanetFValidationAction
{
    /**
     * Validate email and PIN against Planet F (softconnet) API.
     * API returns: { "success": 1, "message": "Account Validated Successfully", "balance": 200 }
     * or { "success": 0, "message": "Incorrect Pin Supplied" } / "Invalid Account Provided"
     *
     * @param string $email
     * @param string $pin
     * @return array{ valid: bool, message?: string, balance?: int }
     */
    public function validate(string $email, string $pin): array
    {
        $url = config('services.planetf.validation_url');
        $apiKey = config('services.planetf.api_key');

        if (empty($url)) {
            Log::warning('Planet F validation URL not configured. Set PLANET_F_VALIDATION_URL in .env');
            return ['valid' => false, 'message' => 'Account validation is not configured.'];
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        if (! empty($apiKey)) {
            $headers['Authorization'] = 'Bearer ' . $apiKey;
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders($headers)
                ->post($url, [
                    'email' => $email,
                    'pin' => $pin,
                ]);

            $body = $response->json() ?? [];
            // API: success 1 = valid, 0 = invalid; message always present
            $success = $body['success'] ?? 0;
            $valid = (int) $success === 1;
            $message = $body['message'] ?? ($valid ? null : 'Invalid email or PIN.');
            $balance = isset($body['balance']) ? (int) $body['balance'] : null;

            if (! $response->successful() && $message === null) {
                $message = 'Validation service temporarily unavailable.';
            }

            $result = ['valid' => $valid, 'message' => $message];
            if ($balance !== null) {
                $result['balance'] = $balance;
            }

            return $result;
        } catch (\Throwable $e) {
            Log::error('Planet F validation error: ' . $e->getMessage());
            return ['valid' => false, 'message' => 'Unable to validate account. Please try again.'];
        }
    }
}
