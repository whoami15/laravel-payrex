<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

class BillingStatementLineItem extends PayrexObject
{
    public readonly ?string $description;

    public readonly ?int $unitPrice;

    public readonly ?int $quantity;

    public readonly ?string $billingStatementId;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->description = $attributes['description'] ?? null;
        $this->unitPrice = $attributes['unit_price'] ?? null;
        $this->quantity = $attributes['quantity'] ?? null;
        $this->billingStatementId = $attributes['billing_statement_id'] ?? null;
    }
}
