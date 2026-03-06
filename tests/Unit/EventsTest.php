<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use LegionHQ\LaravelPayrex\Data\PaymentIntent;
use LegionHQ\LaravelPayrex\Enums\PaymentIntentStatus;
use LegionHQ\LaravelPayrex\Enums\WebhookEventType;
use LegionHQ\LaravelPayrex\Events\BillingStatementCreated;
use LegionHQ\LaravelPayrex\Events\PaymentIntentAwaitingCapture;
use LegionHQ\LaravelPayrex\Events\PaymentIntentSucceeded;
use LegionHQ\LaravelPayrex\Events\PayrexEvent;
use LegionHQ\LaravelPayrex\Events\PayrexWebhookReceived;

it('returns event data resource as a typed DTO via data()', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');

    $event = new PaymentIntentSucceeded($payload);

    expect($event->data())
        ->toBeInstanceOf(PaymentIntent::class)
        ->id->toBe('pi_SJuGtXXC3XNRWpW3W1zQKiLWf67ZC4sX')
        ->resource->toBe('payment_intent')
        ->amount->toBe(10000)
        ->currency->toBe('PHP')
        ->status->toBe(PaymentIntentStatus::AwaitingPaymentMethod)
        ->description->toBe('')
        ->metadata->toBeNull();
});

it('supports array access on data() for raw values', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');

    $event = new PaymentIntentSucceeded($payload);

    expect($event->data()['id'])->toBe('pi_SJuGtXXC3XNRWpW3W1zQKiLWf67ZC4sX')
        ->and($event->data()['status'])->toBe('awaiting_payment_method');
});

it('returns event type as WebhookEventType enum via eventType()', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');

    $event = new PaymentIntentSucceeded($payload);

    expect($event->eventType())->toBe(WebhookEventType::PaymentIntentSucceeded);
});

it('returns null for eventType when type is missing', function () {
    $event = new PaymentIntentSucceeded([]);

    expect($event->eventType())->toBeNull();
});

it('returns null for eventType when type is unknown', function () {
    $event = new PaymentIntentSucceeded(['type' => 'unknown.event']);

    expect($event->eventType())->toBeNull();
});

it('returns false for isLiveMode in test mode', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');

    $event = new PaymentIntentSucceeded($payload);

    expect($event->isLiveMode())->toBeFalse();
});

it('returns true for isLiveMode when livemode is true', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');
    $payload['livemode'] = true;

    $event = new PaymentIntentSucceeded($payload);

    expect($event->isLiveMode())->toBeTrue();
});

it('exposes the full payload via the payload property', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');

    $event = new PaymentIntentSucceeded($payload);

    expect($event->payload)->toBe($payload)
        ->and($event->payload['id'])->toBe('evt_bxuGtXXC3zNsWbW3W1zQKiLWf67ZC4sa')
        ->and($event->payload['resource'])->toBe('event')
        ->and($event->payload['pending_webhooks'])->toBe(1)
        ->and($event->payload['data']['previous_attributes']['status'])->toBe('awaiting_next_action');
});

it('resolves known event types to their class via resolveEventClass', function () {
    expect(PayrexEvent::resolveEventClass('payment_intent.succeeded'))->toBe(PaymentIntentSucceeded::class)
        ->and(PayrexEvent::resolveEventClass('payment_intent.awaiting_capture'))->toBe(PaymentIntentAwaitingCapture::class)
        ->and(PayrexEvent::resolveEventClass('billing_statement.created'))->toBe(BillingStatementCreated::class);
});

it('returns null for unknown event types via resolveEventClass', function () {
    expect(PayrexEvent::resolveEventClass('unknown.event'))->toBeNull()
        ->and(PayrexEvent::resolveEventClass(''))->toBeNull();
});

it('dispatches both generic and typed events via dispatchWebhook', function () {
    Event::fake();

    PayrexEvent::dispatchWebhook([
        'type' => 'payment_intent.succeeded',
        'data' => ['resource' => ['id' => 'pi_123']],
    ]);

    Event::assertDispatched(PayrexWebhookReceived::class);
    Event::assertDispatched(PaymentIntentSucceeded::class);
});

it('dispatches only generic event for unknown types via dispatchWebhook', function () {
    Event::fake();

    PayrexEvent::dispatchWebhook([
        'type' => 'unknown.event',
        'data' => ['resource' => ['id' => 'res_123']],
    ]);

    Event::assertDispatched(PayrexWebhookReceived::class);
    Event::assertNotDispatched(PaymentIntentSucceeded::class);
});

it('dispatches only generic event when type is missing via dispatchWebhook', function () {
    Event::fake();

    PayrexEvent::dispatchWebhook(['data' => ['resource' => ['id' => 'res_123']]]);

    Event::assertDispatched(PayrexWebhookReceived::class);
});
