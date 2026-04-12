<?php

namespace App\Contracts\Telecom;

/**
 * Validates wallet / reseller credentials before debiting a balance-backed order.
 */
interface WalletCredentialsValidator
{
    /**
     * @return array{valid: bool, message?: string, balance?: int|float}
     */
    public function validate(string $email, string $pin): array;
}
