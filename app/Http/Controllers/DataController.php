<?php

namespace App\Http\Controllers;

use App\Actions\DataCsvImportAction;
use App\Actions\PaymentAction;
use App\Actions\ServiceProviderAction;
use App\Contracts\Telecom\WalletCredentialsValidator;
use App\Jobs\DispatchDataFulfillmentChunks;
use App\Models\BulkOrder;
use App\Models\Data;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataController extends Controller
{
    public function index(ServiceProviderAction $serviceProvider)
    {
        $mtn = $serviceProvider->cachedMtn();
        $airtel = $serviceProvider->cachedAirtel();
        $glo = $serviceProvider->cachedGlo();
        $etisalat = $serviceProvider->cachedEtisalat();

        return view('data', compact('mtn', 'airtel', 'glo', 'etisalat'));
    }

    public function importCsv(Request $request, DataCsvImportAction $import, ServiceProviderAction $serviceProvider)
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:51200'],
        ]);

        try {
            $result = $import->import($request->file('csv'), $serviceProvider);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        $bulk = $result['bulk_order'];

        return response()->json([
            'bulk_order_uuid' => $bulk->uuid,
            'total_rows'      => $result['total_rows'],
            'total_amount'    => $result['total_amount'],
            'preview'         => $result['preview'],
        ]);
    }

    public function bulkOrderStatus(string $uuid)
    {
        $bulk = BulkOrder::query()->where('uuid', $uuid)->firstOrFail();
        if (($bulk->service ?? 'data') !== 'data') {
            abort(404);
        }

        return response()->json([
            'uuid'           => $bulk->uuid,
            'status'         => $bulk->status,
            'total_rows'     => $bulk->total_rows,
            'processed_rows' => $bulk->processed_rows,
            'failed_rows'    => $bulk->failed_rows,
            'total_amount'   => (float) $bulk->total_amount,
        ]);
    }

    /**
     * Paginated CSV preview rows for a bulk order (same session / UUID from import-csv).
     */
    public function bulkOrderPreviewRows(Request $request, string $uuid)
    {
        $perPage = min(100, max(1, (int) $request->query('per_page', 50)));
        $page = max(1, (int) $request->query('page', 1));

        $bulk = BulkOrder::query()->where('uuid', $uuid)->first();
        if (! $bulk || ($bulk->service ?? 'data') !== 'data') {
            return response()->json(['message' => 'Bulk order not found.'], 404);
        }

        $base = Data::query()
            ->where('bulk_order_id', $bulk->id)
            ->orderBy('id');

        $total = (clone $base)->count();
        $rows = (clone $base)->forPage($page, $perPage)->get(['phone_number', 'network_code', 'amount']);
        $lastPage = max(1, (int) ceil($total / $perPage));

        return response()->json([
            'rows' => $rows->map(static fn ($d) => [
                'phone_number' => $d->phone_number,
                'network_code' => $d->network_code,
                'amount'       => $d->amount,
            ]),
            'total'      => $total,
            'page'       => $page,
            'per_page'   => $perPage,
            'last_page'  => $lastPage,
        ]);
    }

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

        $validCodes = $allPlans
            ->pluck('coded')
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
        $phone = (array) $request->input('phone', []);

        if (empty($values) || empty($phone)) {
            return response()->json('error', 400);
        }

        $allPlans = collect($serviceProvider->cachedMtn())
            ->concat($serviceProvider->cachedAirtel())
            ->concat($serviceProvider->cachedGlo())
            ->concat($serviceProvider->cachedEtisalat());

        $codeToAmount = [];
        foreach ($allPlans as $plan) {
            if (isset($plan['coded'], $plan['price'])) {
                $codeToAmount[$plan['coded']] = $plan['price'];
            }
        }

        $cellArray = [];
        $header = ['phone_number', 'network_code', 'amount'];
        $cellArray[] = $header;

        foreach ($values as $index => $code) {
            if (! isset($phone[$index])) {
                continue;
            }

            if (! isset($codeToAmount[$code])) {
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

    public function store(
        PaymentAction $payment,
        WalletCredentialsValidator $walletValidator,
        Request $request
    ) {
        return $this->storeFromBulkOrder($payment, $walletValidator, $request);
    }

    private function storeFromBulkOrder(
        PaymentAction $payment,
        WalletCredentialsValidator $walletValidator,
        Request $request
    ) {
        $request->validate([
            'email'             => ['required', 'email'],
            'bulk_order_uuid'   => ['required', 'uuid'],
            'pin'               => ['nullable', 'string', 'max:10'],
        ]);

        $bulk = BulkOrder::query()->where('uuid', $request->bulk_order_uuid)->firstOrFail();
        if (($bulk->service ?? 'data') !== 'data') {
            return response()->json(['message' => 'Invalid bulk order.'], 422);
        }

        if ($bulk->status !== BulkOrder::STATUS_PENDING_PAYMENT) {
            return response()->json([
                'message' => 'This order is no longer awaiting payment.',
            ], 422);
        }

        $email = $request->email;
        $pin = $request->input('pin');
        $totalAmount = (float) $bulk->total_amount;

        if ($pin !== null && $pin !== '') {
            $result = $walletValidator->validate($email, $pin);
            if (! $result['valid']) {
                return response()->json([
                    'message' => $result['message'] ?? 'Invalid email or PIN. Please check your Planet F account details.',
                ], 422);
            }
            $balance = isset($result['balance']) ? (float) $result['balance'] : null;
            if ($balance === null) {
                return response()->json([
                    'message' => 'Your Planet F wallet balance could not be confirmed. Wallet checkout is blocked until the provider returns a balance. Please use card payment or try again.',
                ], 422);
            }
            if ($totalAmount > $balance) {
                return response()->json([
                    'message' => 'Your balance (₦'.number_format($balance, 0).') is less than the order total (₦'.number_format($totalAmount, 0).'). Please top up your Planet F account or reduce the order amount.',
                ], 422);
            }

            $cacheKey = 'bulk_data_wallet:'.$bulk->id.':'.Str::random(32);
            Cache::put($cacheKey, ['email' => $email, 'pin' => $pin], now()->addHours(2));

            $bulk->uploaded_by_email = $email;
            $bulk->pin_cache_key = $cacheKey;
            $bulk->channel = BulkOrder::CHANNEL_WALLET;
            $bulk->save();

            Data::query()->where('bulk_order_id', $bulk->id)->update(['uploaded_by' => $email]);

            DispatchDataFulfillmentChunks::dispatch($bulk->id);

            return response()->json([
                'success' => 1,
                'queued'  => true,
                'message' => 'Your data order has been queued. Processing will complete shortly.',
            ]);
        }

        $payout = $payment->paymentCheckout($email, $totalAmount, Payment::DATA);

        if (! is_array($payout) || ! isset($payout['reference'], $payout['checkout_url'])) {
            return response()->json([
                'message' => 'Unable to initialize data payment at the moment. Please try again.',
            ], 500);
        }

        $reference = $payout['reference'];

        DB::transaction(function () use ($email, $reference, $totalAmount, $bulk): void {
            $pay = new Payment();
            $pay->savePayment("user{$reference}", $email, Payment::DATA, $reference, 'NGN', $totalAmount);
            $pay->bulk_order_id = $bulk->id;
            $pay->save();

            $bulk->uploaded_by_email = $email;
            $bulk->channel = BulkOrder::CHANNEL_KORAPAY;
            $bulk->save();

            Data::query()->where('bulk_order_id', $bulk->id)->update([
                'payment_id'  => $pay->id,
                'uploaded_by' => $email,
            ]);
        });

        return response()->json($payout);
    }

    public function show()
    {
    }

    public function edit(Data $data)
    {
        //
    }

    public function update(Request $request, Data $data)
    {
        //
    }

    public function destroy(Data $data)
    {
        //
    }
}
