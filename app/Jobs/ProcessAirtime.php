<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Airtime;
use App\Actions\PlanetFPurchaseAction;
use Illuminate\Support\Facades\Log;

class ProcessAirtime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $paymentId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Airtime $airtime, PlanetFPurchaseAction $planetFPurchase)
    {
        $airtimes = $airtime->getSuccessfulAirtimePaymentIdAttribute($this->paymentId);

        $rows = [];
        foreach ($airtimes as $row) {
            $rows[] = [
                'service'      => $row->network,
                'amount'       => $row->amount,
                'phone_number' => $row->phone_number,
            ];
        }

        $planetFPurchase->buyAirtimeFromRows($rows, $airtimes[0]->uploaded_by ?? '', ''); // PIN not needed post-Korapay
    }
}
