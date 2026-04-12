<?php

namespace App\Actions;

use App\Jobs\DispatchAirtimeFulfillmentChunks;
use App\Jobs\DispatchDataFulfillmentChunks;
use App\Models\Airtime;
use App\Models\BulkOrder;
use App\Models\Data;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RetryFailedBulkFulfillmentAction
{
    public function resolveBulkOrder(string $identifier): ?BulkOrder
    {
        if (Str::isUuid($identifier)) {
            return BulkOrder::query()->where('uuid', $identifier)->first();
        }
        if (ctype_digit($identifier)) {
            return BulkOrder::query()->find((int) $identifier);
        }

        return null;
    }

    /**
     * Reset failed fulfillment rows to pending and queue Dispatch*FulfillmentChunks.
     *
     * @return array{success: bool, message: string, failed_rows: int, reset_rows: int, dispatched: bool, bulk_order_id: int|null, uuid: string|null}
     */
    public function retry(BulkOrder $bulk, bool $dryRun = false): array
    {
        $base = [
            'bulk_order_id' => $bulk->id,
            'uuid'          => $bulk->uuid,
        ];

        $service = (string) ($bulk->service ?? 'data');

        if ($service === 'airtime') {
            $failedCount = Airtime::query()
                ->where('bulk_order_id', $bulk->id)
                ->where('fulfillment_status', 'failed')
                ->count();
        } else {
            $failedCount = Data::query()
                ->where('bulk_order_id', $bulk->id)
                ->where('fulfillment_status', 'failed')
                ->count();
        }

        if ($failedCount === 0) {
            return array_merge($base, [
                'success'      => true,
                'message'      => 'No failed rows to retry.',
                'failed_rows'  => 0,
                'reset_rows'   => 0,
                'dispatched'   => false,
            ]);
        }

        if (! $bulk->hasSuccessfulFulfillmentPayment()) {
            return array_merge($base, [
                'success'      => false,
                'message'      => 'Retry is only allowed after successful payment: Korapay charges must have payment status success, or wallet checkout must have completed (not still awaiting payment).',
                'failed_rows'  => $failedCount,
                'reset_rows'   => 0,
                'dispatched'   => false,
            ]);
        }

        if ($dryRun) {
            return array_merge($base, [
                'success'      => true,
                'message'      => "Dry run: {$failedCount} failed row(s) would be reset and re-queued.",
                'failed_rows'  => $failedCount,
                'reset_rows'   => $failedCount,
                'dispatched'   => false,
            ]);
        }

        $reset = [
            'fulfillment_status' => 'pending',
            'provider_message'   => null,
        ];

        if ($service === 'airtime') {
            $table = (new Airtime())->getTable();
            if (Schema::hasColumn($table, 'attempts')) {
                $reset['attempts'] = 0;
            }
            Airtime::query()
                ->where('bulk_order_id', $bulk->id)
                ->where('fulfillment_status', 'failed')
                ->update($reset);
            DispatchAirtimeFulfillmentChunks::dispatch($bulk->id);
        } else {
            $table = (new Data())->getTable();
            if (Schema::hasColumn($table, 'attempts')) {
                $reset['attempts'] = 0;
            }
            Data::query()
                ->where('bulk_order_id', $bulk->id)
                ->where('fulfillment_status', 'failed')
                ->update($reset);
            DispatchDataFulfillmentChunks::dispatch($bulk->id);
        }

        return array_merge($base, [
            'success'      => true,
            'message'      => "Reset {$failedCount} failed row(s) to pending and queued fulfillment.",
            'failed_rows'  => $failedCount,
            'reset_rows'   => $failedCount,
            'dispatched'   => true,
        ]);
    }
}
