<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use LegionHQ\LaravelPayrex\Enums\PaymentStatus;

class Payment extends PayrexObject
{
    public readonly ?int $amount;

    public readonly ?int $amountRefunded;

    /** @var array<string, mixed>|null */
    public readonly ?array $billing;

    public readonly ?string $currency;

    public readonly ?string $description;

    public readonly ?int $fee;

    public readonly ?int $netAmount;

    public readonly ?string $paymentIntentId;

    /** @var array<string, mixed>|null */
    public readonly ?array $paymentMethod;

    public readonly ?PaymentStatus $status;

    public readonly string|Customer|null $customer;

    /** @var array<string, mixed>|null */
    public readonly ?array $pageSession;

    public readonly ?bool $refunded;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->amount = $attributes['amount'] ?? null;
        $this->amountRefunded = $attributes['amount_refunded'] ?? null;
        $this->billing = $attributes['billing'] ?? null;
        $this->currency = $attributes['currency'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->fee = $attributes['fee'] ?? null;
        $this->netAmount = $attributes['net_amount'] ?? null;
        $this->paymentIntentId = $attributes['payment_intent_id'] ?? null;
        $this->paymentMethod = $attributes['payment_method'] ?? null;
        $this->status = isset($attributes['status']) ? PaymentStatus::tryFrom($attributes['status']) : null;
        $this->customer = match (true) {
            is_array($attributes['customer'] ?? null) => new Customer($attributes['customer']),
            is_string($attributes['customer'] ?? null) => $attributes['customer'],
            default => null,
        };
        $this->pageSession = $attributes['page_session'] ?? null;
        $this->refunded = $attributes['refunded'] ?? null;
    }
}
