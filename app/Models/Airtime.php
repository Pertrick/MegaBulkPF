<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Airtime extends Model
{
    use HasFactory;

    const PENDING = 0;

    const SENT = 1;

    protected $fillable = [
        'email',
        'data',
        'bulk_order_id',
        'fulfillment_status',
    ];

    protected $casts = [
        'attempts' => 'integer',
    ];

    public function bulkOrder(): BelongsTo
    {
        return $this->belongsTo(BulkOrder::class, 'bulk_order_id');
    }


    public function payment(){
        return $this->belongsTo(Payment::class);
    }
}
