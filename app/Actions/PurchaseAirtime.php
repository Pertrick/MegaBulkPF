<?php

namespace App\Actions;

use App\Models\Airtime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PurchaseAirtime
{

    public function buyAirtime($airtimes)
    {
        foreach ($airtimes as $airtime) {

            $id = $airtime->id;

            $payload = [
                "service" => "airtime",
                "coded" => $this->coded($airtime->network),
                "phone" => $airtime->phone_number,
                "amount" => $airtime->amount
            ];

            $response = Http::withHeaders([
                'Authorization' => env('MCD_PAYMENT_KEY'),
                'Content-Type' => 'application/json'
            ])
            ->withoutVerifying() // replaces CURLOPT_SSL_VERIFYHOST & CURLOPT_SSL_VERIFYPEER
            ->post(env('MCD_PAY_URL'), $payload);

            $data = $response->json();

            if (($data['success'] ?? 0) == 1) {
                $record = Airtime::where('id', $id)->first();
                $record->status = Airtime::SENT;
                $record->sent_at = Carbon::now();
                $record->save();
            }
        }

        return true;
    }


    public function coded($network)
    {
        switch ($network) {
            case "MTN":
                return "m";
            case "AIRTEL":
                return "a";
            case "9MOBILE":
                return "9";
            case "GLO":
                return "g";
        }

        return null;
    }
}