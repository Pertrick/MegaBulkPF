<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlanetFPurchaseAction
{
    /**
     * Buy airtime for existing Planet F users (wallet flow) using CSV rows.
     *
     * @param array<int, array<string, mixed>> $rows
     * @param string $email
     * @param string $pin
     * @return array{success: bool, message: string}
     */
    public function buyAirtimeFromRows(array $rows, string $email, string $pin): array
    {
        $url = config('services.planetf.airtime_url') ?: 'https://planetf.com.ng/api/v2/bulk/airtime';

        foreach ($rows as $index => $row) {
            $provider = strtoupper((string) ($row['service'] ?? $row['provider'] ?? ''));
            $amount   = $row['amount'] ?? null;
            $number   = (string) ($row['phone_number'] ?? $row['number'] ?? '');

            if ($provider === '' || $amount === null || $number === '') {
                return [
                    'success' => false,
                    'message' => 'One of the airtime rows is missing provider, amount or phone number (row ' . ($index + 1) . ').',
                ];
            }

            try {
                $response = Http::timeout(20)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept'       => 'application/json',
                    ])
                    ->post($url, [
                        'provider' => $provider,
                        'amount'   => (string) $amount,
                        'number'   => $number,
                        'email'    => $email,
                        'pin'      => $pin,
                    ]);

                $body = $response->json() ?? [];
                $success = (int) ($body['success'] ?? 0) === 1;

                if (! $success) {
                    $message = $body['message'] ?? 'Airtime purchase failed for ' . $number;
                    return [
                        'success' => false,
                        'message' => $message,
                    ];
                }
            } catch (\Throwable $e) {
                Log::error('Planet F airtime purchase error: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Unable to complete airtime purchase at the moment. Please try again.',
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Airtime purchase successful. Your requests are being processed.',
        ];
    }

    /**
     * Buy data for existing Planet F users (wallet flow) using updated table rows.
     *
     * @param array<int, array<string, mixed>> $rows
     * @param string $email
     * @param string $pin
     * @return array{success: bool, message: string}
     */
    public function buyDataFromRows(array $rows, string $email, string $pin): array
    {
        $url   = config('services.planetf.data_url') ?: 'https://planetf.com.ng/api/v2/bulk/data';
        $token = config('services.planetf.data_token');

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];
        if (! empty($token)) {
            // Token may already include Bearer prefix; we send as-is.
            $headers['Authorization'] = $token;
        }

        foreach ($rows as $index => $row) {
            $code   = (string) ($row['network_code'] ?? $row['coded'] ?? $row['code'] ?? '');
            $number = (string) ($row['phone_number'] ?? $row['number'] ?? '');

            if ($code === '' || $number === '') {
                return [
                    'success' => false,
                    'message' => 'One of the data rows is missing code or phone number (row ' . ($index + 1) . ').',
                ];
            }

            try {
                $response = Http::timeout(20)
                    ->withHeaders($headers)
                    ->post($url, [
                        'coded'  => $code,
                        'number' => $number,
                        'email'  => $email,
                        'pin'    => $pin,
                    ]);

                $body = $response->json() ?? [];
                $success = (int) ($body['success'] ?? 0) === 1;

                if (! $success) {
                    $message = $body['message'] ?? 'Data purchase failed for ' . $number;
                    return [
                        'success' => false,
                        'message' => $message,
                    ];
                }
            } catch (\Throwable $e) {
                Log::error('Planet F data purchase error: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Unable to complete data purchase at the moment. Please try again.',
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Data purchase successful. Your requests are being processed.',
        ];
    }
}

