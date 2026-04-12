<?php

namespace App\Contracts\Telecom;

use Illuminate\Support\Collection;

/**
 * Provider-agnostic bulk fulfillment (per persisted row).
 * Implementations wrap a specific telecom API (Planet F today, another vendor tomorrow).
 */
interface TelecomFulfillmentGateway
{
    /**
     * @param  Collection<int, \App\Models\Airtime>  $rows
     * @return array<int, array{success: bool, message: string}>
     */
    public function fulfillAirtimeRows(Collection $rows, string $email, string $pin): array;

    /**
     * @param  Collection<int, \App\Models\Data>  $rows
     * @return array<int, array{success: bool, message: string}>
     */
    public function fulfillDataRows(Collection $rows, string $email, string $pin): array;
}
