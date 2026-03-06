<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use LegionHQ\LaravelPayrex\Enums\PaymentIntentStatus;

class PaymentIntent extends PayrexObject
{
    public readonly ?int $amount;

    public readonly ?int $amountReceived;

    public readonly ?int $amountCapturable;

    public readonly ?string $clientSecret;

    public readonly ?string $currency;

    public readonly ?string $description;

    /** @var array<string, mixed>|null */
    public readonly ?array $lastPaymentError;

    public readonly mixed $latestPayment;

    /** @var array<string, mixed>|null */
    public readonly ?array $nextAction;

    /** @var array<string, mixed>|null */
    public readonly ?array $paymentMethodOptions;

    /** @var array<int, string>|null */
    public readonly ?array $paymentMethods;

    public readonly ?string $statementDescriptor;

    public readonly ?PaymentIntentStatus $status;

    public readonly ?string $paymentMethodId;

    public readonly ?int $captureBeforeAt;

    public readonly mixed $customer;

    public readonly ?string $returnUrl;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->amount = $attributes['amount'] ?? null;
        $this->amountReceived = $attributes['amount_received'] ?? null;
        $this->amountCapturable = $attributes['amount_capturable'] ?? null;
        $this->clientSecret = $attributes['client_secret'] ?? null;
        $this->currency = $attributes['currency'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->lastPaymentError = $attributes['last_payment_error'] ?? null;
        $this->latestPayment = $attributes['latest_payment'] ?? null;
        $this->nextAction = $attributes['next_action'] ?? null;
        $this->paymentMethodOptions = $attributes['payment_method_options'] ?? null;
        $this->paymentMethods = $attributes['payment_methods'] ?? null;
        $this->statementDescriptor = $attributes['statement_descriptor'] ?? null;
        $this->status = isset($attributes['status']) ? PaymentIntentStatus::tryFrom($attributes['status']) : null;
        $this->paymentMethodId = $attributes['payment_method_id'] ?? null;
        $this->captureBeforeAt = $attributes['capture_before_at'] ?? null;
        $this->customer = $attributes['customer'] ?? null;
        $this->returnUrl = $attributes['return_url'] ?? null;
    }
}
