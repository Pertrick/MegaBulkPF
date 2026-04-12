<?php

namespace App\Jobs;

use App\Contracts\Telecom\TelecomFulfillmentGateway;
use App\Models\BulkOrder;
use App\Models\Data;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FulfillDataChunkJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /**
     * @var int
     */
    public $timeout = 3600;

    public function __construct(
        public int $bulkOrderId,
        /** @var array<int> */
        public array $dataRowIds,
        public string $fulfillmentEmail = '',
        public string $fulfillmentPin = ''
    ) {
        $this->timeout = max(120, count($dataRowIds) * ((int) config('bulk.provider_http_timeout', config('bulk.planetf_http_timeout', 25)) + 2));
    }

    public function handle(TelecomFulfillmentGateway $gateway): void
    {
        $bulk = BulkOrder::query()->find($this->bulkOrderId);
        if (! $bulk) {
            return;
        }

        $rows = collect();
        DB::transaction(function () use (&$rows): void {
            $locked = Data::query()
                ->whereIn('id', $this->dataRowIds)
                ->where('bulk_order_id', $this->bulkOrderId)
                ->where('fulfillment_status', 'pending')
                ->lockForUpdate()
                ->get();

            foreach ($locked as $row) {
                $row->update([
                    'fulfillment_status' => 'processing',
                    'attempts'           => $row->attempts + 1,
                    'last_attempt_at'    => now(),
                ]);
            }

            $rows = $locked;
        });

        if ($rows->isEmpty()) {
            BulkOrder::reconcileCompletion($this->bulkOrderId);

            return;
        }

        $results = $gateway->fulfillDataRows(
            $rows,
            $this->fulfillmentEmail !== '' ? $this->fulfillmentEmail : $bulk->uploaded_by_email,
            $this->fulfillmentPin
        );

        foreach ($results as $id => $result) {
            Data::query()->whereKey($id)->update([
                'fulfillment_status' => ($result['success'] ?? false) ? 'sent' : 'failed',
                'provider_message'   => Str::limit((string) ($result['message'] ?? ''), 65000),
                'status'             => ($result['success'] ?? false) ? 1 : 0,
            ]);
        }

        BulkOrder::reconcileCompletion($this->bulkOrderId);
    }

    public function failed(\Throwable $e): void
    {
        Data::query()
            ->whereIn('id', $this->dataRowIds)
            ->where('bulk_order_id', $this->bulkOrderId)
            ->where('fulfillment_status', 'processing')
            ->update(['fulfillment_status' => 'pending']);
    }
}
