<?php

declare(strict_types=1);

use LegionHQ\LaravelPayrex\Data\PayrexObject;
use LegionHQ\LaravelPayrex\Enums\WebhookEventType;
use LegionHQ\LaravelPayrex\Events\PaymentIntentSucceeded;
use LegionHQ\LaravelPayrex\Events\PayrexWebhookReceived;
use LegionHQ\LaravelPayrex\Exceptions\WebhookVerificationException;
use LegionHQ\LaravelPayrex\PayrexWebhook;

function buildWebhookSignature(string $payload, string $secret, ?int $timestamp = null): string
{
    $timestamp = $timestamp ?? time();
    $signature = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

    return "t={$timestamp}, te={$signature}, li=";
}

it('constructs a typed event from a valid webhook payload', function () {
    $payload = json_encode([
        'id' => 'evt_123',
        'type' => 'payment_intent.succeeded',
        'livemode' => false,
        'data' => ['resource' => ['id' => 'pi_123', 'amount' => 50000]],
    ]);

    $header = buildWebhookSignature($payload, 'whsec_test');
    $event = PayrexWebhook::constructEvent($payload, $header, 'whsec_test');

    expect($event)
        ->toBeInstanceOf(PaymentIntentSucceeded::class)
        ->eventType()->toBe(WebhookEventType::PaymentIntentSucceeded)
        ->data()->toBeInstanceOf(PayrexObject::class)
        ->and($event->data()->id)->toBe('pi_123')
        ->and($event->data()['amount'])->toBe(50000)
        ->and($event->isLiveMode())->toBeFalse();
});

it('falls back to PayrexWebhookReceived for unknown event types', function () {
    $payload = json_encode([
        'id' => 'evt_456',
        'type' => 'unknown.event',
        'livemode' => false,
        'data' => ['resource' => ['id' => 'res_456']],
    ]);

    $header = buildWebhookSignature($payload, 'whsec_test');
    $event = PayrexWebhook::constructEvent($payload, $header, 'whsec_test');

    expect($event)
        ->toBeInstanceOf(PayrexWebhookReceived::class)
        ->and($event->eventType())->toBeNull();
});

it('throws on invalid signature', function () {
    $payload = json_encode(['type' => 'payment_intent.succeeded']);
    $timestamp = time();
    $header = "t={$timestamp}, te=invalid, li=";

    PayrexWebhook::constructEvent($payload, $header, 'whsec_test');
})->throws(WebhookVerificationException::class);

it('throws on invalid JSON payload', function () {
    $payload = 'not-json';
    $header = buildWebhookSignature($payload, 'whsec_test');

    PayrexWebhook::constructEvent($payload, $header, 'whsec_test');
})->throws(WebhookVerificationException::class, 'Invalid JSON payload.');

it('respects custom tolerance', function () {
    $payload = json_encode(['type' => 'payment_intent.succeeded', 'data' => ['resource' => []]]);
    $oldTimestamp = time() - 600;
    $header = buildWebhookSignature($payload, 'whsec_test', $oldTimestamp);

    PayrexWebhook::constructEvent($payload, $header, 'whsec_test', tolerance: 0);
})->throwsNoExceptions();
