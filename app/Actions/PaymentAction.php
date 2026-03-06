<?php

namespace App\Actions;

use App\Jobs\ProcessAirtime;
use App\Jobs\ProcessData;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentAction
{
    public function paymentCheckout($email, $amount)
    {
        $reference = substr(md5(uniqid()), 0, 10);
        $user = "user_$reference";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('KORAPAY_SECRET_KEY'),
        ])->post('https://api.korapay.com/merchant/api/v1/charges/initialize', [
            'reference' => $reference,
            'amount' => $amount,
            'currency' => 'NGN',
            'redirect_url' => env('APP_URL') . 'verify',
            'customer' => [
                'name' => $user,
                'email' => $email,
            ],
            'metadata' => [
                'service' => 'airtime'
            ],
        ]);

        return $response->json()['data'] ?? null;
    }


    public function verify(Request $request)
    {
        $ref = $request->query('reference');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('KORAPAY_SECRET_KEY'),
        ])->get("https://api.korapay.com/merchant/api/v1/charges/{$ref}");

        $rep = $response->json();

        if (($rep['status'] ?? false) && ($rep['data']['status'] ?? null) === "success") {

            $payment = Payment::where('reference_id', $rep['data']['reference'])->first();

            if (!$payment) {
                return false;
            }

            $payment->status = Payment::SUCCESS;

            if ($payment->save()) {

                if ($payment->service == Payment::AIRTIME) {
                    ProcessAirtime::dispatch($payment->id);
                } elseif ($payment->service == Payment::DATA) {
                    ProcessData::dispatch($payment->id);
                }

                return true;
            }
        }

        return false;
    }
}