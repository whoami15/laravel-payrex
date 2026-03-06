<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

class Customer extends PayrexObject
{
    public readonly ?string $name;

    public readonly ?string $email;

    public readonly ?string $currency;

    public readonly ?string $billingStatementPrefix;

    public readonly ?string $nextBillingStatementSequenceNumber;

    /** @var array<string, mixed>|null */
    public readonly ?array $billing;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->name = $attributes['name'] ?? null;
        $this->email = $attributes['email'] ?? null;
        $this->currency = $attributes['currency'] ?? null;
        $this->billingStatementPrefix = $attributes['billing_statement_prefix'] ?? null;
        $this->nextBillingStatementSequenceNumber = $attributes['next_billing_statement_sequence_number'] ?? null;
        $this->billing = $attributes['billing'] ?? null;
    }
}
