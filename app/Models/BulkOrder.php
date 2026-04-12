<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BulkOrder extends Model
{
    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    public const STATUS_PAID = 'paid';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_COMPLETED_WITH_ERRORS = 'completed_with_errors';

    public const CHANNEL_KORAPAY = 'korapay';

    public const CHANNEL_WALLET = 'wallet';

    protected $fillable = [
        'uuid',
        'service',
        'uploaded_by_email',
        'channel',
        'payment_id',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'status',
        'total_amount',
        'pin_cache_key',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function dataRows(): HasMany
    {
        return $this->hasMany(Data::class, 'bulk_order_id');
    }

    public function airtimeRows(): HasMany
    {
        return $this->hasMany(Airtime::class, 'bulk_order_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Korapay: payment row must be success before Planet F fulfillment. Wallet: checkout must have left pending_payment.
     */
    public function hasSuccessfulFulfillmentPayment(): bool
    {
        if (($this->channel ?? '') === self::CHANNEL_WALLET) {
            return $this->status !== self::STATUS_PENDING_PAYMENT;
        }

        if ($this->payment_id) {
            $payment = Payment::query()->find($this->payment_id);

            return $payment !== null && $payment->status === Payment::SUCCESS;
        }

        return Payment::query()
            ->where('bulk_order_id', $this->id)
            ->where('status', Payment::SUCCESS)
            ->exists();
    }

    protected static function booted(): void
    {
        static::creating(function (BulkOrder $order): void {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * When no rows are left pending, mark bulk order complete and clear wallet PIN cache.
     */
    public static function reconcileCompletion(int $bulkOrderId): void
    {
        DB::transaction(function () use ($bulkOrderId): void {
            $bulk = static::query()->lockForUpdate()->find($bulkOrderId);
            if (! $bulk) {
                return;
            }

            $service = Schema::hasColumn($bulk->getTable(), 'service')
                ? (string) ($bulk->service ?: 'data')
                : 'data';

            if ($service === 'airtime') {
                if (Airtime::query()
                    ->where('bulk_order_id', $bulkOrderId)
                    ->whereIn('fulfillment_status', ['pending', 'processing'])
                    ->count() > 0) {
                    return;
                }

                $failed = Airtime::query()
                    ->where('bulk_order_id', $bulkOrderId)
                    ->where('fulfillment_status', 'failed')
                    ->count();

                $processed = Airtime::query()
                    ->where('bulk_order_id', $bulkOrderId)
                    ->whereIn('fulfillment_status', ['sent', 'failed'])
                    ->count();
            } else {
                if (Data::query()
                    ->where('bulk_order_id', $bulkOrderId)
                    ->whereIn('fulfillment_status', ['pending', 'processing'])
                    ->count() > 0) {
                    return;
                }

                $failed = Data::query()
                    ->where('bulk_order_id', $bulkOrderId)
                    ->where('fulfillment_status', 'failed')
                    ->count();

                $processed = Data::query()
                    ->where('bulk_order_id', $bulkOrderId)
                    ->whereIn('fulfillment_status', ['sent', 'failed'])
                    ->count();
            }

            $bulk->failed_rows = $failed;
            $bulk->processed_rows = $processed;
            $bulk->status = $failed > 0 ? self::STATUS_COMPLETED_WITH_ERRORS : self::STATUS_COMPLETED;
            $bulk->save();
        });
    }
}
