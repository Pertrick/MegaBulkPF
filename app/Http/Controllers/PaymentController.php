<?php

namespace App\Http\Controllers;

use App\Actions\PaymentAction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{

    /**
     * Korapay return URL: verify payment and show result.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function verifyPayment(PaymentAction $paymentAction, Request $request)
    {
        $referenceId = $paymentAction->korapayRedirectReference($request);
        $success = $paymentAction->verify($request);

        if (! $success && $referenceId !== '') {
            Log::info('Korapay /verify: charge not confirmed as success.', [
                'ip'               => $request->ip(),
                'reference_length' => strlen($referenceId),
            ]);
        }

        $paymentModel = $referenceId !== ''
            ? Payment::where('reference_id', $referenceId)->first()
            : null;

        return view('payment.result', [
            'success' => $success,
            'payment' => $paymentModel,
        ]);
    }
}
