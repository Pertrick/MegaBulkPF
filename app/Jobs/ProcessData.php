<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Data;
use App\Actions\PlanetFPurchaseAction;
use Illuminate\Support\Facades\Log;

class ProcessData implements ShouldQueue
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
    public function handle(Data $data, PlanetFPurchaseAction $planetFPurchase)
    {
        $records = $data->getSuccessfulDataPaymentAttribute($this->paymentId);

        $rows = [];
        foreach ($records as $row) {
            $rows[] = [
                'network_code' => $row->network_code,
                'phone_number' => $row->phone_number,
            ];
        }

        $planetFPurchase->buyDataFromRows($rows, $records[0]->uploaded_by ?? '', ''); // PIN not needed post-Korapay
    }
}
