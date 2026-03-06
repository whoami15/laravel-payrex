<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use LegionHQ\LaravelPayrex\Enums\PayoutStatus;

class Payout extends PayrexObject
{
    public readonly ?int $amount;

    public readonly ?int $netAmount;

    public readonly ?PayoutStatus $status;

    /** @var array<string, mixed>|null */
    public readonly ?array $destination;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->amount = $attributes['amount'] ?? null;
        $this->netAmount = $attributes['net_amount'] ?? null;
        $this->status = isset($attributes['status']) ? PayoutStatus::tryFrom($attributes['status']) : null;
        $this->destination = $attributes['destination'] ?? null;
    }
}
