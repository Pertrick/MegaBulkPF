<?php

namespace App\Http\Controllers;

use App\Actions\RetryFailedBulkFulfillmentAction;
use App\Models\BulkOrder;

class BulkRetryController extends Controller
{
    public function __invoke(string $uuid, RetryFailedBulkFulfillmentAction $action)
    {
        $bulk = BulkOrder::query()->where('uuid', $uuid)->first();
        if (! $bulk) {
            return response('Bulk order not found.', 404)->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        $result = $action->retry($bulk, false);

        $status = $result['success'] ? 200 : 422;
        $lines = [
            $result['message'],
            '',
            'Bulk order #'.$result['bulk_order_id'].' — service: '.(string) ($bulk->service ?? 'data'),
            'Failed rows: '.$result['failed_rows'].' — Reset: '.$result['reset_rows'].' — Queued: '.($result['dispatched'] ? 'yes' : 'no'),
        ];
        if ($result['dispatched']) {
            $lines[] = '';
            $lines[] = 'Fulfillment jobs were queued. Keep queue:work running.';
        }

        return response(implode("\n", $lines), $status)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
