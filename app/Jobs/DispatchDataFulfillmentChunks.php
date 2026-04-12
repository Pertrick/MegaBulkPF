<?php

namespace App\Jobs;

use App\Models\BulkOrder;
use App\Models\Data;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DispatchDataFulfillmentChunks implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Drop duplicate dispatch jobs for the same bulk order (webhook + redirect, retries).
     */
    public int $uniqueFor = 120;

    public function __construct(
        public int $bulkOrderId
    ) {
    }

    public function uniqueId(): string
    {
        return 'bulk-data-dispatch-'.$this->bulkOrderId;
    }

    public function handle(): void
    {
        $bulk = BulkOrder::query()->find($this->bulkOrderId);
        if (! $bulk || ($bulk->service ?? 'data') !== 'data') {
            return;
        }

        if (($bulk->channel ?? '') === BulkOrder::CHANNEL_KORAPAY && ! $bulk->hasSuccessfulFulfillmentPayment()) {
            Log::warning('DispatchDataFulfillmentChunks skipped: Korapay bulk order has no successful payment yet.', [
                'bulk_order_id' => $this->bulkOrderId,
            ]);

            return;
        }

        $email = $bulk->uploaded_by_email;
        $pin = '';
        if ($bulk->pin_cache_key) {
            $cached = Cache::pull($bulk->pin_cache_key);
            $bulk->pin_cache_key = null;
            $bulk->save();
            if (is_array($cached)) {
                $email = $cached['email'] ?? $email;
                $pin = (string) ($cached['pin'] ?? '');
            }
        } elseif (($bulk->channel ?? '') === BulkOrder::CHANNEL_KORAPAY) {
            $defaultEmail = trim((string) config('services.planetf.default_bulk_fulfillment_email', ''));
            if ($defaultEmail !== '') {
                $email = $defaultEmail;
            }
        }

        BulkOrder::query()->whereKey($this->bulkOrderId)->update([
            'status' => BulkOrder::STATUS_PROCESSING,
        ]);

        $ids = Data::query()
            ->where('bulk_order_id', $this->bulkOrderId)
            ->where('fulfillment_status', 'pending')
            ->orderBy('id')
            ->pluck('id');

        if ($ids->isEmpty()) {
            BulkOrder::reconcileCompletion($this->bulkOrderId);

            return;
        }

        $chunkSize = max(1, (int) config('bulk.chunk_size', 150));
        foreach ($ids->chunk($chunkSize) as $chunk) {
            FulfillDataChunkJob::dispatch($this->bulkOrderId, $chunk->values()->all(), $email, $pin);
        }
    }
}
