<?php

namespace App\Actions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Fetches and caches public data bundle catalog from the configured upstream.
 * Credentials must come from config/env — never hardcode keys in source.
 */
class ServiceProviderAction
{
    private function catalogBaseUrl(): string
    {
        return (string) config('services.planetf.plan_catalog_base_url', 'https://planetf.com.ng/api/v2/');
    }

    private function catalogAuthorizationHeader(): string
    {
        return (string) (config('services.planetf.plan_catalog_authorization') ?? '');
    }

    private function request(?array $payload = null, ?string $url = null, string $method = 'POST'): mixed
    {
        $auth = $this->catalogAuthorizationHeader();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];
        if ($auth !== '') {
            $headers['Authorization'] = $auth;
        }

        $fullUrl = $this->catalogBaseUrl().ltrim((string) $url, '/');

        if ($payload !== null) {
            return Http::withHeaders($headers)->$method($fullUrl, $payload)->json();
        }

        return Http::withHeaders($headers)->$method($fullUrl)->json();
    }

    public function airtime()
    {
        $response = $this->request(null, 'airtime', 'GET');

        return $response['data'] ?? [];
    }

    public function mtnData()
    {
        $response = $this->request(null, 'data/MTN', 'GET');

        return $response ?? [];
    }

    public function airtelData()
    {
        $response = $this->request(null, 'data/AIRTEL', 'GET');

        return $response ?? null;
    }

    public function etisalatData()
    {
        $response = $this->request(null, 'data/9MOBILE', 'GET');

        return $response ?? null;
    }

    public function gloData()
    {
        $response = $this->request(null, 'data/GLO', 'GET');

        return $response ?? null;
    }

    public function cachedMtn()
    {
        return Cache::remember('mtn', 3600, function () {
            $mtnData = $this->mtnData();

            return $mtnData['data'] ?? [];
        });
    }

    public function cachedAirtel()
    {
        return Cache::remember('airtel', 3600, function () {
            $airtelData = $this->airtelData();

            return $airtelData['data'] ?? [];
        });
    }

    public function cachedGlo()
    {
        return Cache::remember('glo', 3600, function () {
            $gloData = $this->gloData();

            return $gloData['data'] ?? [];
        });
    }

    public function cachedEtisalat()
    {
        return Cache::remember('etisalat', 3600, function () {
            $etisalatData = $this->etisalatData();

            return $etisalatData['data'] ?? [];
        });
    }
}
