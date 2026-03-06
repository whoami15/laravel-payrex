<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use LegionHQ\LaravelPayrex\Enums\BillingStatementStatus;

class BillingStatement extends PayrexObject
{
    public readonly ?int $amount;

    public readonly ?string $currency;

    public readonly ?string $customerId;

    public readonly ?BillingStatementStatus $status;

    public readonly ?string $description;

    public readonly ?string $url;

    public readonly ?string $billingDetailsCollection;

    public readonly ?string $billingStatementMerchantName;

    public readonly ?string $billingStatementNumber;

    public readonly ?string $billingStatementUrl;

    public readonly ?int $dueAt;

    public readonly ?int $finalizedAt;

    /** @var array<int, array<string, mixed>>|null */
    public readonly ?array $lineItems;

    public readonly string|Customer|null $customer;

    public readonly string|PaymentIntent|null $paymentIntent;

    /** @var array<string, mixed>|null */
    public readonly ?array $paymentSettings;

    public readonly ?string $setupFutureUsage;

    public readonly ?string $statementDescriptor;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->amount = $attributes['amount'] ?? null;
        $this->currency = $attributes['currency'] ?? null;
        $this->customerId = $attributes['customer_id'] ?? null;
        $this->status = isset($attributes['status']) ? BillingStatementStatus::tryFrom($attributes['status']) : null;
        $this->description = $attributes['description'] ?? null;
        $this->url = $attributes['url'] ?? null;
        $this->billingDetailsCollection = $attributes['billing_details_collection'] ?? null;
        $this->billingStatementMerchantName = $attributes['billing_statement_merchant_name'] ?? null;
        $this->billingStatementNumber = $attributes['billing_statement_number'] ?? null;
        $this->billingStatementUrl = $attributes['billing_statement_url'] ?? null;
        $this->dueAt = $attributes['due_at'] ?? null;
        $this->finalizedAt = $attributes['finalized_at'] ?? null;
        $this->lineItems = $attributes['line_items'] ?? null;
        $this->customer = match (true) {
            is_array($attributes['customer'] ?? null) => new Customer($attributes['customer']),
            is_string($attributes['customer'] ?? null) => $attributes['customer'],
            default => null,
        };
        $this->paymentIntent = match (true) {
            is_array($attributes['payment_intent'] ?? null) => new PaymentIntent($attributes['payment_intent']),
            is_string($attributes['payment_intent'] ?? null) => $attributes['payment_intent'],
            default => null,
        };
        $this->paymentSettings = $attributes['payment_settings'] ?? null;
        $this->setupFutureUsage = $attributes['setup_future_usage'] ?? null;
        $this->statementDescriptor = $attributes['statement_descriptor'] ?? null;
    }
}
