<?php

namespace App\Actions;

use App\Jobs\DispatchAirtimeFulfillmentChunks;
use App\Jobs\DispatchDataFulfillmentChunks;
use App\Models\BulkOrder;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentAction
{
    /**
     * After a payment is marked successful, update bulk order and queue telecom fulfillment.
     * Safe to call from redirect verify or server webhooks; idempotent when payment is already SUCCESS if callers guard.
     */
    public function handleSuccessfulPayment(Payment $payment): void
    {
        if ($payment->status !== Payment::SUCCESS) {
            Log::warning('handleSuccessfulPayment called with non-success payment; skipping.', [
                'payment_id' => $payment->id,
                'status'     => $payment->status,
            ]);

            return;
        }

        if ($payment->bulk_order_id) {
            $bulk = BulkOrder::query()->find($payment->bulk_order_id);
            if ($bulk) {
                $expected = round((float) $bulk->total_amount, 2);
                $paid = round((float) $payment->amount, 2);
                if (abs($expected - $paid) > 0.02) {
                    Log::critical('Payment amount does not match bulk order total; fulfillment not queued.', [
                        'payment_id'    => $payment->id,
                        'bulk_order_id' => $bulk->id,
                        'bulk_total'    => $expected,
                        'payment_amount'=> $paid,
                    ]);

                    return;
                }
            }

            BulkOrder::query()->whereKey($payment->bulk_order_id)->update([
                'status' => BulkOrder::STATUS_PAID,
            ]);
        }

        if ($payment->service === Payment::AIRTIME) {
            if ($payment->bulk_order_id) {
                DispatchAirtimeFulfillmentChunks::dispatch($payment->bulk_order_id);
            } else {
                Log::warning('Airtime payment succeeded without bulk_order_id; fulfillment skipped.', [
                    'payment_id' => $payment->id,
                ]);
            }
        } elseif ($payment->service === Payment::DATA) {
            if ($payment->bulk_order_id) {
                DispatchDataFulfillmentChunks::dispatch($payment->bulk_order_id);
            } else {
                Log::warning('Data payment succeeded without bulk_order_id; fulfillment skipped.', [
                    'payment_id' => $payment->id,
                ]);
            }
        }
    }

    /**
     * @param  string  $korapayMetadataService  Label stored on Korapay metadata (e.g. airtime|data).
     */
    public function paymentCheckout(string $email, float|int|string $amount, string $korapayMetadataService = 'data'): ?array
    {
        // Cryptographically strong, unguessable reference for redirect + Korapay charge lookup (not short md5).
        $reference = Str::lower(Str::random(32));
        $user = 'user_'.$reference;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env('KORAPAY_SECRET_KEY'),
        ])->post('https://api.korapay.com/merchant/api/v1/charges/initialize', [
            'reference'    => $reference,
            'amount'       => $amount,
            'currency'     => 'NGN',
            'redirect_url' => rtrim(config('app.url'), '/').'/verify',
            'customer'     => [
                'name'  => $user,
                'email' => $email,
            ],
            'metadata'     => [
                'service' => $korapayMetadataService,
            ],
        ]);

        return $response->json()['data'] ?? null;
    }


    public function verify(Request $request)
    {
        $ref = $this->korapayReferenceFromRequest($request);
        if ($ref === '') {
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('KORAPAY_SECRET_KEY'),
        ])->get('https://api.korapay.com/merchant/api/v1/charges/'.rawurlencode($ref));

        $rep = $response->json();

        if (($rep['status'] ?? false) && ($rep['data']['status'] ?? null) === 'success') {

            $referenceFromApi = (string) ($rep['data']['reference'] ?? $ref);
            $payment = Payment::where('reference_id', $referenceFromApi)->first();

            if (! $payment) {
                return false;
            }

            if ($payment->status === Payment::SUCCESS) {
                return true;
            }

            $payment->status = Payment::SUCCESS;

            if ($payment->save()) {
                $this->handleSuccessfulPayment($payment);

                return true;
            }
        }

        return false;
    }

    /** Reference Korapay may append to redirect_url (for display / DB lookup). */
    public function korapayRedirectReference(Request $request): string
    {
        return $this->korapayReferenceFromRequest($request);
    }

    /**
     * Korapay appends ?reference=… to redirect_url; accept a few aliases for resilience.
     */
    private function korapayReferenceFromRequest(Request $request): string
    {
        foreach (['reference', 'transaction_reference', 'payment_reference', 'trx_ref'] as $key) {
            $v = $request->query($key);
            if (is_string($v) && trim($v) !== '') {
                return trim($v);
            }
        }

        return '';
    }
}