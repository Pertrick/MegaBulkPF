<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Data extends Model
{
    use HasFactory;

    const PENDING = 0;

    const SENT = 1;

    protected static ?string $resolvedTable = null;

    protected $fillable = [
        'email',
        'data',
        'bulk_order_id',
        'fulfillment_status',
    ];

    public function getTable()
    {
        if (self::$resolvedTable === null) {
            self::$resolvedTable = Schema::hasTable('data') ? 'data' : 'datas';
        }

        return self::$resolvedTable;
    }

    public function bulkOrder(): BelongsTo
    {
        return $this->belongsTo(BulkOrder::class, 'bulk_order_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

}