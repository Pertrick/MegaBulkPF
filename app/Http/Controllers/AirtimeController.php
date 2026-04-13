<?php

namespace App\Http\Controllers;

use App\Actions\AirtimeCsvImportAction;
use App\Actions\PaymentAction;
use App\Contracts\Telecom\WalletCredentialsValidator;
use App\Jobs\DispatchAirtimeFulfillmentChunks;
use App\Models\Airtime;
use App\Models\BulkOrder;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AirtimeController extends Controller
{
    public function index()
    {
        return view('airtime');
    }

    public function importCsv(Request $request, AirtimeCsvImportAction $import)
    {
        $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:51200'],
        ]);

        try {
            $result = $import->import($request->file('csv'));
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
        if (($bulk->service ?? '') !== 'airtime') {
            abort(404);
        }

        return response()->json([
            'uuid'             => $bulk->uuid,
            'status'           => $bulk->status,
            'total_rows'       => $bulk->total_rows,
            'processed_rows'   => $bulk->processed_rows,
            'failed_rows'      => $bulk->failed_rows,
            'total_amount'     => (float) $bulk->total_amount,
        ]);
    }

    public function bulkOrderPreviewRows(Request $request, string $uuid)
    {
        $perPage = min(100, max(1, (int) $request->query('per_page', 50)));
        $page = max(1, (int) $request->query('page', 1));

        $bulk = BulkOrder::query()->where('uuid', $uuid)->first();
        if (! $bulk || ($bulk->service ?? '') !== 'airtime') {
            return response()->json(['message' => 'Bulk order not found.'], 404);
        }

        $base = Airtime::query()
            ->where('bulk_order_id', $bulk->id)
            ->orderBy('id');

        $total = (clone $base)->count();
        $rows = (clone $base)->forPage($page, $perPage)->get(['phone_number', 'network', 'amount']);
        $lastPage = max(1, (int) ceil($total / $perPage));

        return response()->json([
            'rows' => $rows->map(static fn ($a) => [
                'phone_number' => $a->phone_number,
                'network'      => $a->network,
                'amount'       => $a->amount,
            ]),
            'total'      => $total,
            'page'       => $page,
            'per_page'   => $perPage,
            'last_page'  => $lastPage,
        ]);
    }

    /**
     * Checkout for a server-imported bulk airtime order (requires bulk_order_uuid).
     */
    public function store(
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
        if (($bulk->service ?? '') !== 'airtime') {
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

            $cacheKey = 'bulk_airtime_wallet:'.$bulk->id.':'.Str::random(32);
            Cache::put($cacheKey, ['email' => $email, 'pin' => $pin], now()->addHours(2));

            $bulk->uploaded_by_email = $email;
            $bulk->pin_cache_key = $cacheKey;
            $bulk->channel = BulkOrder::CHANNEL_WALLET;
            $bulk->save();

            Airtime::query()->where('bulk_order_id', $bulk->id)->update(['uploaded_by' => $email]);

            DispatchAirtimeFulfillmentChunks::dispatch($bulk->id);

            return response()->json([
                'success' => 1,
                'queued'  => true,
                'message' => 'Your airtime order has been queued. Processing will complete shortly.',
            ]);
        }

        $payout = $payment->paymentCheckout($email, $totalAmount, Payment::AIRTIME);

        if (! is_array($payout) || ! isset($payout['reference'], $payout['checkout_url'])) {
            return response()->json([
                'message' => 'Unable to initialize airtime payment at the moment. Please try again.',
            ], 500);
        }

        $reference = $payout['reference'];

        DB::transaction(function () use ($email, $reference, $totalAmount, $bulk): void {
            $pay = new Payment();
            $pay->savePayment("user{$reference}", $email, Payment::AIRTIME, $reference, 'NGN', $totalAmount);
            $pay->bulk_order_id = $bulk->id;
            $pay->save();

            $bulk->uploaded_by_email = $email;
            $bulk->channel = BulkOrder::CHANNEL_KORAPAY;
            $bulk->save();

            Airtime::query()->where('bulk_order_id', $bulk->id)->update([
                'payment_id'  => $pay->id,
                'uploaded_by' => $email,
            ]);
        });

        return response()->json($payout);
    }

    public function show()
    {
    }

    public function edit(Airtime $airtime)
    {
        //
    }

    public function update(Request $request, Airtime $airtime)
    {
        //
    }

    public function destroy(Airtime $airtime)
    {
        //
    }
}
