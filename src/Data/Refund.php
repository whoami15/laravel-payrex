<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use LegionHQ\LaravelPayrex\Enums\RefundReason;
use LegionHQ\LaravelPayrex\Enums\RefundStatus;

class Refund extends PayrexObject
{
    public readonly ?int $amount;

    public readonly ?string $currency;

    public readonly ?RefundStatus $status;

    public readonly ?string $description;

    public readonly ?RefundReason $reason;

    public readonly ?string $remarks;

    public readonly ?string $paymentId;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->amount = $attributes['amount'] ?? null;
        $this->currency = $attributes['currency'] ?? null;
        $this->status = isset($attributes['status']) ? RefundStatus::tryFrom($attributes['status']) : null;
        $this->description = $attributes['description'] ?? null;
        $this->reason = isset($attributes['reason']) ? RefundReason::tryFrom($attributes['reason']) : null;
        $this->remarks = $attributes['remarks'] ?? null;
        $this->paymentId = $attributes['payment_id'] ?? null;
    }
}
