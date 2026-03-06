<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use LegionHQ\LaravelPayrex\Enums\CheckoutSessionStatus;

class CheckoutSession extends PayrexObject
{
    public readonly ?int $amount;

    public readonly ?string $clientSecret;

    public readonly ?string $currency;

    public readonly ?string $customerReferenceId;

    public readonly ?string $description;

    public readonly ?CheckoutSessionStatus $status;

    public readonly ?string $url;

    /** @var array<int, array<string, mixed>>|null */
    public readonly ?array $lineItems;

    public readonly ?string $successUrl;

    public readonly ?string $cancelUrl;

    public readonly mixed $paymentIntent;

    /** @var array<int, string>|null */
    public readonly ?array $paymentMethods;

    /** @var array<string, mixed>|null */
    public readonly ?array $paymentMethodOptions;

    public readonly ?string $billingDetailsCollection;

    public readonly ?string $submitType;

    public readonly ?string $statementDescriptor;

    public readonly ?int $expiresAt;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->amount = $attributes['amount'] ?? null;
        $this->clientSecret = $attributes['client_secret'] ?? null;
        $this->currency = $attributes['currency'] ?? null;
        $this->customerReferenceId = $attributes['customer_reference_id'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->status = isset($attributes['status']) ? CheckoutSessionStatus::tryFrom($attributes['status']) : null;
        $this->url = $attributes['url'] ?? null;
        $this->lineItems = $attributes['line_items'] ?? null;
        $this->successUrl = $attributes['success_url'] ?? null;
        $this->cancelUrl = $attributes['cancel_url'] ?? null;
        $this->paymentIntent = $attributes['payment_intent'] ?? null;
        $this->paymentMethods = $attributes['payment_methods'] ?? null;
        $this->paymentMethodOptions = $attributes['payment_method_options'] ?? null;
        $this->billingDetailsCollection = $attributes['billing_details_collection'] ?? null;
        $this->submitType = $attributes['submit_type'] ?? null;
        $this->statementDescriptor = $attributes['statement_descriptor'] ?? null;
        $this->expiresAt = $attributes['expires_at'] ?? null;
    }
}
