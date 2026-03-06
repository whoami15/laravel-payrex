<?php

declare(strict_types=1);

use LegionHQ\LaravelPayrex\PayrexClient;

it('registers the payrex client as a singleton', function () {
    $client = app(PayrexClient::class);

    expect($client)->toBeInstanceOf(PayrexClient::class);
    expect(app(PayrexClient::class))->toBe($client);
});

it('resolves the payrex alias', function () {
    expect(app('payrex'))->toBeInstanceOf(PayrexClient::class);
});

it('has the payment intents resource', function () {
    $client = app(PayrexClient::class);

    expect($client->paymentIntents)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\PaymentIntentResource::class);
});

it('has the payments resource', function () {
    $client = app(PayrexClient::class);

    expect($client->payments)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\PaymentResource::class);
});

it('has the refunds resource', function () {
    $client = app(PayrexClient::class);

    expect($client->refunds)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\RefundResource::class);
});

it('has the customers resource', function () {
    $client = app(PayrexClient::class);

    expect($client->customers)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\CustomerResource::class);
});

it('has the checkout sessions resource', function () {
    $client = app(PayrexClient::class);

    expect($client->checkoutSessions)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\CheckoutSessionResource::class);
});

it('has the webhooks resource', function () {
    $client = app(PayrexClient::class);

    expect($client->webhooks)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\WebhookResource::class);
});

it('has the billing statements resource', function () {
    $client = app(PayrexClient::class);

    expect($client->billingStatements)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\BillingStatementResource::class);
});

it('has the payout transactions resource', function () {
    $client = app(PayrexClient::class);

    expect($client->payoutTransactions)
        ->toBeInstanceOf(\LegionHQ\LaravelPayrex\Resources\PayoutTransactionResource::class);
});
