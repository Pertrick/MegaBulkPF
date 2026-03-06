<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ServiceProviderAction
{
    private $baseUrl = 'https://transactiontest.mcd.5starcompany.com.ng/api/v1/';
    private $apiKey = 'mcd_key_fertyuilokmjnhgft56789807675434265fd';

    private function request($payload=null, $url = null, $method = 'POST')
    {
        if($payload){
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->$method($this->baseUrl . $url, $payload);
        }else{
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->$method($this->baseUrl . $url);
        }
        return $response->json();
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
        return Cache::rememberForever('mtn', function () {
            $mtnData = $this->mtnData();
            return $mtnData['data'] ?? [];
        });
    }

    public function cachedAirtel()
    {
        return Cache::rememberForever('airtel', function () {
            $airtelData = $this->airtelData();
            return $airtelData['data'] ?? [];
        });
    }

    public function cachedGlo()
    {
        return Cache::rememberForever('glo', function () {
            $gloData = $this->gloData();
            return $gloData['data'] ?? [];
        });
    }

    public function cachedEtisalat()
    {
        return Cache::rememberForever('etisalat', function () {
            $etisalatData = $this->etisalatData();
            return $etisalatData['data'] ?? [];
        });
    }
}