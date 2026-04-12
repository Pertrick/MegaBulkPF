<?php

namespace App\Actions;

use App\Models\BulkOrder;
use App\Models\Data;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DataCsvImportAction
{
    /**
     * Parse data CSV (phone_number, code or network_code; optional amount ignored). Validates against provider plans.
     *
     * @return array{bulk_order: BulkOrder, preview: array<int, array<string, mixed>>, total_rows: int, total_amount: float}
     */
    public function import(UploadedFile $file, ServiceProviderAction $serviceProvider): array
    {
        $allPlans = collect($serviceProvider->cachedMtn())
            ->concat($serviceProvider->cachedAirtel())
            ->concat($serviceProvider->cachedGlo())
            ->concat($serviceProvider->cachedEtisalat());

        if ($allPlans->isEmpty()) {
            throw new \RuntimeException('No provider data available. Please try again later.');
        }

        $codeToAmount = [];
        foreach ($allPlans as $plan) {
            if (isset($plan['coded'], $plan['price'])) {
                $codeToAmount[$plan['coded']] = $plan['price'];
            }
        }

        $validCodes = $allPlans->pluck('coded')->filter()->unique()->flip();

        $path = $file->getRealPath();
        if (! $path || ! is_readable($path)) {
            throw new \RuntimeException('Unable to read CSV file.');
        }

        $handle = fopen($path, 'r');
        if (! $handle) {
            throw new \RuntimeException('Unable to open CSV file.');
        }

        $header = fgetcsv($handle);
        if (! $header) {
            fclose($handle);
            throw new \RuntimeException('CSV is empty.');
        }

        $header = array_map(static fn ($h) => strtolower(trim((string) $h)), $header);
        $phoneIdx = array_search('phone_number', $header, true);
        $codeIdx = array_search('code', $header, true);
        if ($codeIdx === false) {
            $codeIdx = array_search('network_code', $header, true);
        }
        if ($phoneIdx === false || $codeIdx === false) {
            fclose($handle);
            throw new \RuntimeException('CSV must include phone_number and a plan column: code or network_code.');
        }

        $parsed = [];
        $lineNo = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;
            if (! isset($row[$phoneIdx], $row[$codeIdx])) {
                continue;
            }
            $phone = trim((string) $row[$phoneIdx]);
            $code = trim((string) $row[$codeIdx]);
            if ($phone === '' && $code === '') {
                continue;
            }
            if (strlen($phone) !== 11 || $phone[0] !== '0') {
                fclose($handle);
                throw new \RuntimeException("Invalid phone on line {$lineNo}: must be 11 digits starting with 0.");
            }
            if (! isset($validCodes[$code])) {
                fclose($handle);
                throw new \RuntimeException("Invalid plan code on line {$lineNo}: {$code}");
            }
            if (! isset($codeToAmount[$code])) {
                fclose($handle);
                throw new \RuntimeException("No amount for code on line {$lineNo}: {$code}");
            }
            $parsed[] = [
                'phone_number' => $phone,
                'network_code' => $code,
                'amount'       => $codeToAmount[$code],
            ];
        }
        fclose($handle);

        if ($parsed === []) {
            throw new \RuntimeException('No data rows found in CSV.');
        }

        $totalAmount = array_sum(array_map(static fn ($r) => (float) $r['amount'], $parsed));

        $bulk = DB::transaction(function () use ($parsed, $totalAmount) {
            $order = new BulkOrder();
            $order->uuid = (string) Str::uuid();
            $order->service = 'data';
            $order->uploaded_by_email = '';
            $order->total_rows = count($parsed);
            $order->total_amount = $totalAmount;
            $order->status = BulkOrder::STATUS_PENDING_PAYMENT;
            $order->save();

            $now = now();
            $table = (new Data())->getTable();
            $hasLegacyNetwork = Schema::hasColumn($table, 'network');
            foreach (array_chunk($parsed, 500) as $chunk) {
                $insert = [];
                foreach ($chunk as $r) {
                    $row = [
                        'phone_number'       => $r['phone_number'],
                        'network_code'       => $r['network_code'],
                        'amount'             => $r['amount'],
                        'status'             => 0,
                        'uploaded_by'        => '',
                        'bulk_order_id'      => $order->id,
                        'fulfillment_status' => 'pending',
                        'payment_id'         => null,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ];
                    if ($hasLegacyNetwork) {
                        $row['network'] = $r['network_code'];
                    }
                    $insert[] = $row;
                }
                Data::query()->insert($insert);
            }

            return $order->fresh();
        });

        return [
            'bulk_order'   => $bulk,
            'preview'      => array_slice($parsed, 0, 20),
            'total_rows'   => count($parsed),
            'total_amount' => $totalAmount,
        ];
    }
}
