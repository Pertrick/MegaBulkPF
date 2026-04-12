<?php

namespace App\Console\Commands;

use App\Actions\RetryFailedBulkFulfillmentAction;
use App\Models\BulkOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class BulkRetryFailedCommand extends Command
{
    protected $signature = 'bulk:retry-failed
                            {bulk : Bulk order numeric ID or UUID}
                            {--dry-run : Count failed rows without changing anything}
                            {--url-expires=168 : Hours until the printed signed retry URL expires (default: 168 = 7 days)}';

    protected $description = 'Reset failed bulk data/airtime rows to pending and re-queue fulfillment (successful Korapay payment or completed wallet checkout only)';

    public function handle(RetryFailedBulkFulfillmentAction $action): int
    {
        $identifier = trim((string) $this->argument('bulk'));
        $bulk = $action->resolveBulkOrder($identifier);

        if (! $bulk instanceof BulkOrder) {
            $this->error('Bulk order not found. Use a numeric ID or a valid UUID.');

            return self::FAILURE;
        }

        $service = (string) ($bulk->service ?? 'data');
        $this->line("Bulk order #{$bulk->id} ({$service}) — UUID: {$bulk->uuid}");

        $result = $action->retry($bulk, (bool) $this->option('dry-run'));

        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['Failed rows (before)', (string) $result['failed_rows']],
                ['Reset / re-queued', (string) $result['reset_rows']],
                ['Dispatch queued', $result['dispatched'] ? 'yes' : 'no'],
            ]
        );

        if (! $result['dispatched'] && ! $this->option('dry-run') && $result['failed_rows'] > 0 && ! $result['success']) {
            $this->comment('Confirm Korapay payment is success (or complete wallet checkout) then run again.');
        }

        if ($result['dispatched']) {
            $this->comment('Ensure a queue worker is running (e.g. php artisan queue:work).');
        }

        $hours = max(1, (int) $this->option('url-expires'));
        $retryUrl = URL::temporarySignedRoute(
            'bulk.retry',
            now()->addHours($hours),
            ['uuid' => $bulk->uuid]
        );
        $this->newLine();
        $this->info('Signed retry link (bookmark or open in browser; expires in '.$hours.'h):');
        $this->line($retryUrl);

        if ($bulk->channel === BulkOrder::CHANNEL_WALLET) {
            $this->warn('Wallet orders: PIN was consumed on first dispatch. Retries use PLANET_F_DEFAULT_BULK_DATA_PIN / Korapay defaults unless you pay from wallet again with a fresh checkout.');
        }

        return $result['success'] ? self::SUCCESS : self::FAILURE;
    }
}
