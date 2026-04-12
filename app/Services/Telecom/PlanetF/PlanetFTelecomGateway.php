<?php

namespace App\Services\Telecom\PlanetF;

use App\Contracts\Telecom\TelecomFulfillmentGateway;
use App\Models\Airtime;
use App\Models\Data;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class PlanetFTelecomGateway implements TelecomFulfillmentGateway
{
    private function httpTimeout(): int
    {
        return (int) config('bulk.provider_http_timeout', config('bulk.planetf_http_timeout', 25));
    }

    private function throttleMicroseconds(): int
    {
        return max(0, (int) config('bulk.throttle_ms_between_requests', 0)) * 1000;
    }

    public function fulfillAirtimeRows(Collection $rows, string $email, string $pin): array
    {
        $url = config('services.planetf.airtime_url') ?: 'https://planetf.com.ng/api/v2/bulk/airtime';
        $timeout = $this->httpTimeout();
        $throttleUs = $this->throttleMicroseconds();
        $pinForApi = $this->resolveBulkApiPin($pin);

        $results = [];
        $first = true;

        /** @var Airtime $row */
        foreach ($rows as $row) {
            if (! $first && $throttleUs > 0) {
                usleep($throttleUs);
            }
            $first = false;

            $provider = strtoupper((string) $row->network);
            $amount = $row->amount;
            $number = (string) $row->phone_number;

            if ($provider === '' || $amount === null || $amount === '' || $number === '') {
                $results[$row->id] = [
                    'success' => false,
                    'message' => 'Missing provider, amount, or phone number.',
                ];

                continue;
            }

            try {
                $payload = [
                    'provider' => $provider,
                    'amount'   => (string) $amount,
                    'number'   => $number,
                    'email'    => $email,
                    'pin'      => $pinForApi,
                ];
                $logPayload = $payload;
                $logPayload['pin'] = $pinForApi === '' ? '(empty)' : '[REDACTED]';
                Log::debug('Planet F airtime purchase request', [
                    'url'             => $url,
                    'airtime_row_id'  => $row->id,
                    'payload'         => $logPayload,
                ]);

                $response = Http::timeout($timeout)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept'       => 'application/json',
                    ])
                    ->post($url, $payload);

                $body = $response->json() ?? [];
                $success = (int) ($body['success'] ?? 0) === 1;

                $results[$row->id] = [
                    'success' => $success,
                    'message' => $success
                        ? ($body['message'] ?? 'OK')
                        : ($body['message'] ?? 'Airtime purchase failed for '.$number),
                ];
            } catch (\Throwable $e) {
                Log::error('Planet F airtime chunk error: '.$e->getMessage());
                $results[$row->id] = [
                    'success' => false,
                    'message' => 'Unable to complete airtime purchase at the moment.',
                ];
            }
        }

        return $results;
    }

    public function fulfillDataRows(Collection $rows, string $email, string $pin): array
    {
        $url = config('services.planetf.data_url') ?: 'https://planetf.com.ng/api/v2/bulk/data';
        $token = config('services.planetf.data_token');
        $timeout = $this->httpTimeout();
        $throttleUs = $this->throttleMicroseconds();
        $pinForApi = $this->resolveBulkApiPin($pin);

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];
        if (! empty($token)) {
            $headers['Authorization'] = $token;
        }

        $results = [];
        $first = true;

        /** @var Data $row */
        foreach ($rows as $row) {
            if (! $first && $throttleUs > 0) {
                usleep($throttleUs);
            }
            $first = false;

            $code = (string) $row->network_code;
            $number = (string) $row->phone_number;

            if ($code === '' || $number === '') {
                $results[$row->id] = [
                    'success' => false,
                    'message' => 'Missing code or phone number.',
                ];

                continue;
            }

            try {
                $payload = [
                    'coded'  => $code,
                    'number' => $number,
                    'email'  => $email,
                    'pin'    => $pinForApi,
                ];
                $logPayload = $payload;
                $logPayload['pin'] = $pinForApi === '' ? '(empty)' : '[REDACTED]';
                Log::debug('Planet F data purchase request', [
                    'url'          => $url,
                    'data_row_id'  => $row->id,
                    'has_auth_hdr' => ! empty($token),
                    'payload'      => $logPayload,
                ]);

                $response = Http::timeout($timeout)
                    ->withHeaders($headers)
                    ->post($url, $payload);

                $body = $response->json() ?? [];
                $success = (int) ($body['success'] ?? 0) === 1;

                $results[$row->id] = [
                    'success' => $success,
                    'message' => $success
                        ? ($body['message'] ?? 'OK')
                        : ($body['message'] ?? 'Data purchase failed for '.$number),
                ];
            } catch (\Throwable $e) {
                Log::error('Planet F data chunk error: '.$e->getMessage());
                $results[$row->id] = [
                    'success' => false,
                    'message' => 'Unable to complete data purchase at the moment.',
                ];
            }
        }

        return $results;
    }

    private function resolveBulkApiPin(string $pin): string
    {
        $trimmed = trim($pin);

        if ($trimmed !== '') {
            return $trimmed;
        }

        return trim((string) config('services.planetf.default_bulk_data_pin', ''));
    }
}
