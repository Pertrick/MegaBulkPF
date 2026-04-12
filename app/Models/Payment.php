<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Payment extends Model
{
    use HasFactory;

    const AIRTIME = 'airtime';

    const DATA = 'data';

    const PENDING = 'pending';

    const SUCCESS = 'success';

    protected $fillable = [
        'user',
        'email',
        'service',
        'reference_id',
        'currency',
        'amount',
        'status',
        'bulk_order_id',
    ];

    public function bulkOrder()
    {
        return $this->belongsTo(BulkOrder::class);
    }

    public function savePayment($user, $email, $service, $reference, $naira, $amount)
    {
        $this->user = $user;
        $this->email = $email;
        $this->service  = $service;
        $this->reference_id = $reference;
        $this->currency = $naira;
        $this->amount = $amount;
        
        $this->save();

    }
}
