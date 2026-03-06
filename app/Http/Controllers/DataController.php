<?php

namespace App\Http\Controllers;

use App\Actions\PaymentAction;
use App\Actions\ServiceProviderAction;
use App\Models\Data;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ServiceProviderAction $serviceProvider)
    {

        $mtn = $serviceProvider->cachedMtn();
        $airtel = $serviceProvider->cachedAirtel();
        $glo = $serviceProvider->cachedGlo();
        $etisalat = $serviceProvider->cachedEtisalat();

        return view('data', compact('mtn', 'airtel', 'glo', 'etisalat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateValues(ServiceProviderAction $serviceProvider, Request $request)
    {
        $values = (array) $request->input('values', []);

        if (empty($values)) {
            return response()->json([
                'status'        => 'error',
                'message'       => 'No codes supplied. Please upload a CSV with at least one row.',
                'invalid_codes' => [],
            ], 200);
        }

        $allPlans = collect($serviceProvider->cachedMtn())
            ->concat($serviceProvider->cachedAirtel())
            ->concat($serviceProvider->cachedGlo())
            ->concat($serviceProvider->cachedEtisalat());

        if ($allPlans->isEmpty()) {
            return response()->json([
                'status'        => 'error',
                'message'       => 'No provider data available. Please try again later.',
                'invalid_codes' => [],
            ], 200);
        }

        // Build a fast lookup set of valid codes.
        $validCodes = $allPlans
            ->pluck('code')
            ->filter()
            ->unique()
            ->flip();

        $submitted = collect($values)->filter()->values();

        $invalid = $submitted->reject(function ($code) use ($validCodes) {
            return isset($validCodes[$code]);
        });

        if ($invalid->isNotEmpty()) {
            return response()->json([
                'status'        => 'error',
                'message'       => 'Some of the submitted codes are not valid for the current providers.',
                'invalid_codes' => $invalid->values(),
            ], 200);
        }

        return response()->json([
            'status'        => 'ok',
            'message'       => 'All submitted codes are valid.',
            'invalid_codes' => [],
        ], 200);
    }


    public function updateTable(ServiceProviderAction $serviceProvider, Request $request)
    {
        $values = (array) $request->input('values', []);
        $phone  = (array) $request->input('phone', []);

        if (empty($values) || empty($phone)) {
            return response()->json('error', 400);
        }

        $allPlans = collect($serviceProvider->cachedMtn())
            ->concat($serviceProvider->cachedAirtel())
            ->concat($serviceProvider->cachedGlo())
            ->concat($serviceProvider->cachedEtisalat());

        // Build a map: code => amount for quick lookup.
        $codeToAmount = [];
        foreach ($allPlans as $plan) {
            if (isset($plan['code'], $plan['amount'])) {
                $codeToAmount[$plan['code']] = $plan['amount'];
            }
        }

        $cellArray = [];
        $header = ['phone_number', 'network_code', 'amount'];
        $cellArray[] = $header;

        foreach ($values as $index => $code) {
            if (!isset($phone[$index])) {
                continue;
            }

            if (!isset($codeToAmount[$code])) {
                continue;
            }

            $cellArray[] = [
                $phone[$index],
                $code,
                $codeToAmount[$code],
            ];
        }

        if (count($cellArray) === 1) {
            return response()->json('empty data table', 400);
        }

        return response()->json($cellArray, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentAction $payment, Request $request)
    {

        $request->validate([
            'email' => ['required', 'email'],
            'data' => ['required', 'json'],
        ]);

        $total_amount = 0;
        $email = $request->email;
        $uploadedData = trim($request->data);

        $uploadedData = Json_decode($uploadedData, true);
    
        foreach ($uploadedData as ["amount" => $amount]) {
            $total_amount = $total_amount + $amount;
        }

        $payout = $payment->paymentCheckout($email, $total_amount);

        if (!is_array($payout) || !isset($payout['reference'], $payout['checkout_url'])) {
            return response()->json([
                'message' => 'Unable to initialize data payment at the moment. Please try again.',
            ], 500);
        }

        $reference = $payout['reference'];

        DB::transaction(function () use ($email, $uploadedData, $reference, $total_amount): void {

            $payment = new Payment();
            $payment->savePayment("user$reference", $email, Payment::DATA, $reference, 'NGN', $total_amount);

            $paymentId = $payment->id;
            foreach ($uploadedData as $value) {
                $data = new Data();
                $data->saveData($value, $email, $paymentId);
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
    public function edit(Data $data)
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
    public function update(Request $request, Data $data)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(Data $data)
    {
        //
    }
}
