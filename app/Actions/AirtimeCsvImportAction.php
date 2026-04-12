<?php

namespace App\Actions;

use App\Models\Airtime;
use App\Models\BulkOrder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AirtimeCsvImportAction
{
    private const VALID_NETWORKS = ['MTN', 'AIRTEL', 'GLO', '9MOBILE'];

    /**
     * Parse airtime CSV (phone_number, service or network; optional extra columns ignored), persist under a new bulk order.
     *
     * @return array{bulk_order: BulkOrder, preview: array<int, array<string, mixed>>, total_rows: int, total_amount: float}
     */
    public function import(UploadedFile $file): array
    {
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
        $serviceIdx = array_search('service', $header, true);
        if ($serviceIdx === false) {
            $serviceIdx = array_search('network', $header, true);
        }
        if ($phoneIdx === false || $serviceIdx === false) {
            fclose($handle);
            throw new \RuntimeException('CSV must include phone_number and service (or network).');
        }

        $amountIdx = array_search('amount', $header, true);

        $parsed = [];
        $lineNo = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;
            if (! isset($row[$phoneIdx], $row[$serviceIdx])) {
                continue;
            }
            $phone = trim((string) $row[$phoneIdx]);
            $serviceRaw = trim((string) $row[$serviceIdx]);
            $amountStr = $amountIdx !== false && isset($row[$amountIdx]) ? trim((string) $row[$amountIdx]) : '';
            if ($phone === '' && $serviceRaw === '') {
                continue;
            }
            if (strlen($phone) !== 11 || $phone[0] !== '0') {
                fclose($handle);
                throw new \RuntimeException("Invalid phone on line {$lineNo}: must be 11 digits starting with 0.");
            }
            $network = strtoupper($serviceRaw);
            if (! in_array($network, self::VALID_NETWORKS, true)) {
                fclose($handle);
                throw new \RuntimeException("Invalid network on line {$lineNo}: use MTN, AIRTEL, GLO, or 9MOBILE (got {$serviceRaw}).");
            }
            if ($amountStr === '' || ! is_numeric($amountStr) || (float) $amountStr <= 0) {
                fclose($handle);
                throw new \RuntimeException("Invalid amount on line {$lineNo}.");
            }
            $amount = round((float) $amountStr, 2);
            $parsed[] = [
                'phone_number' => $phone,
                'network'      => $network,
                'amount'       => $amount,
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
            $order->service = 'airtime';
            $order->uploaded_by_email = '';
            $order->total_rows = count($parsed);
            $order->total_amount = $totalAmount;
            $order->status = BulkOrder::STATUS_PENDING_PAYMENT;
            $order->save();

            $now = now();
            foreach (array_chunk($parsed, 500) as $chunk) {
                $insert = [];
                foreach ($chunk as $r) {
                    $insert[] = [
                        'phone_number'       => $r['phone_number'],
                        'network'            => $r['network'],
                        'amount'             => (string) $r['amount'],
                        'status'             => (string) Airtime::PENDING,
                        'fulfillment_status' => 'pending',
                        'uploaded_by'        => '',
                        'bulk_order_id'      => $order->id,
                        'payment_id'         => null,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ];
                }
                Airtime::query()->insert($insert);
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
