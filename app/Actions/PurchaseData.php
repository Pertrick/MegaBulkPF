<?php

namespace App\Actions;

use App\Models\Data;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PurchaseData
{
    public function buyData($data)
    {
        foreach ($data as $value) {

            $payload = [
                "service" => "data",
                "coded" => $value->network_code,
                "phone" => $value->phone_number
            ];

            $response = Http::withHeaders([
                'Authorization' => env('MCD_PAYMENT_KEY'),
                'Content-Type' => 'application/json'
            ])
            ->withoutVerifying()
            ->post(env('MCD_PAY_URL'), $payload);

            $result = $response->json();

            if (($result['success'] ?? 0) == 1) {
                $value->status = Data::SENT;
                $value->sent_at = Carbon::now();
                $value->save();
            }
        }

        return true;
    }

}