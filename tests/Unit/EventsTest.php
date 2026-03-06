<?php

declare(strict_types=1);

use LegionHQ\LaravelPayrex\Events\PaymentIntentSucceeded;

it('returns event data resource via data()', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');

    $event = new PaymentIntentSucceeded($payload);

    expect($event->data())
        ->toBe($payload['data']['resource'])
        ->and($event->data()['id'])->toBe('pi_SJuGtXXC3XNRWpW3W1zQKiLWf67ZC4sX')
        ->and($event->data()['resource'])->toBe('payment_intent')
        ->and($event->data()['amount'])->toBe(10000)
        ->and($event->data()['currency'])->toBe('PHP')
        ->and($event->data()['status'])->toBe('awaiting_payment_method')
        ->and($event->data()['description'])->toBe('')
        ->and($event->data()['metadata'])->toBeNull();
});

it('returns event type via eventType()', function () {
    $payload = loadFixture('event/payment_intent_succeeded.json');

    $event = new PaymentIntentSucceeded($payload);

    expect($event->eventType())->toBe('payment_intent.succeeded');
});

it('returns null for eventType when type is missing', function () {
    $event = new PaymentIntentSucceeded([]);

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
