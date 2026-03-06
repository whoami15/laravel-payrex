<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

class PayoutTransaction extends PayrexObject
{
    public readonly ?int $amount;

    public readonly ?int $netAmount;

    public readonly ?string $transactionId;

    public readonly ?string $transactionType;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->amount = $attributes['amount'] ?? null;
        $this->netAmount = $attributes['net_amount'] ?? null;
        $this->transactionId = $attributes['transaction_id'] ?? null;
        $this->transactionType = $attributes['transaction_type'] ?? null;
    }
}
