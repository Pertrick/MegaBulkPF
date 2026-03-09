<?php

namespace App\Http\Controllers;

use App\Actions\PaymentAction;
use App\Actions\PlanetFValidationAction;
use App\Actions\PlanetFPurchaseAction;
use App\Models\Airtime;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AirtimeController extends Controller
{
    public function index()
    {
        return view('airtime');
    }

    /**
     * Store a newly created resource in storage.
     * Accepts optional 'pin' for existing Planet F users; validates email+pin when present.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        PaymentAction $payment,
        PlanetFValidationAction $planetFValidation,
        PlanetFPurchaseAction $planetFPurchase,
        Request $request
    )
    {
        $request->validate([
            'email' => ['required', 'email'],
            'data' => ['required', 'json'],
            'pin' => ['nullable', 'string', 'max:10'],
        ]);

        $email        = $request->email;
        $pin          = $request->input('pin');
        $uploadedData = json_decode($request->data, true);
        $total_amount = 0;
        foreach ($uploadedData as ['amount' => $amount]) {
            $total_amount += (float) $amount;
        }

        $balance = null;
        if ($pin !== null && $pin !== '') {
            $result = $planetFValidation->validate($email, $pin);
            if (! $result['valid']) {
                return response()->json([
                    'message' => $result['message'] ?? 'Invalid email or PIN. Please check your Planet F account details.',
                ], 422);
            }
            $balance = isset($result['balance']) ? (float) $result['balance'] : null;
        }

        if ($balance !== null && $total_amount > $balance) {
            return response()->json([
                'message' => 'Your balance (₦' . number_format($balance, 0) . ') is less than the order total (₦' . number_format($total_amount, 0) . '). Please top up your Planet F account or reduce the order amount.',
            ], 422);
        }

        // Existing Planet F user: use PlanetF wallet API directly, no Korapay.
        if ($pin !== null && $pin !== '') {
            $result = $planetFPurchase->buyAirtimeFromRows($uploadedData, $email, $pin);
            if (! $result['success']) {
                return response()->json([
                    'message' => $result['message'] ?? 'Unable to complete airtime purchase. Please try again.',
                ], 422);
            }

            return response()->json([
                'success' => 1,
                'message' => $result['message'] ?? 'Airtime purchase successful.',
            ]);
        }

        // New user (no PIN): go through Korapay checkout as before.
        $payout = $payment->paymentCheckout($email, $total_amount);

        if (! is_array($payout) || ! isset($payout['reference'], $payout['checkout_url'])) {
            return response()->json([
                'message' => 'Unable to initialize airtime payment at the moment. Please try again.',
            ], 500);
        }

        $reference = $payout['reference'];

        DB::transaction(function () use ($email, $uploadedData, $reference, $total_amount): void {
            $payment = new Payment();
            $payment->savePayment("user$reference", $email, Payment::AIRTIME, $reference, 'NGN', $total_amount);

            $paymentId = $payment->id;
            foreach ($uploadedData as $value) {
                $data = new Airtime();
                $data->saveAirtime($value, $email, $paymentId);
            }
        });

        return response()->json($payout);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function edit(Airtime $airtime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Airtime $airtime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Airtime $airtime)
    {
        //
    }

}
