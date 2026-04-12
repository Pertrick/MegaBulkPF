<?php

namespace App\Actions;

use App\Actions\PaymentAction;
use App\Http\Requests\Api\PaymentWebhookRequest;
use App\Models\Payment;

class PaymentWebhookAction
{
    public function __construct(
        private readonly PaymentAction $paymentAction
    ) {
    }

    public function webhook(PaymentWebhookRequest $request): bool
    {
        $secret = (string) (env('KORAPAY_WEBHOOK_SECRET')
            ?: env('KORAPAY_SECRET_KEY')
            ?: env('KORAPAY_SECRET', ''));

        if ($secret === '') {
            return false;
        }

        $hash = hash_hmac(
            'sha256',
            json_encode($request->data),
            $secret,
            false
        );

        if ($request->headers->get('x-korapay-signature') !== $hash) {
            return false;
        }

        if (($request->data['status'] ?? null) !== 'success') {
            return false;
        }

        $payment = Payment::query()
            ->where('reference_id', $request->data['reference'] ?? '')
            ->first();

        if (! $payment) {
            return false;
        }

        if ($payment->status === Payment::SUCCESS) {
            return true;
        }

        $payment->status = Payment::SUCCESS;
        $payment->save();

        $this->paymentAction->handleSuccessfulPayment($payment);

        return true;
    }
}
