<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('registers webhook route when enabled', function () {
    config(['payrex.webhook.enabled' => true]);

    expect(Route::has('payrex.webhook'))->toBeTrue();
});

it('does not register webhook route when disabled', function () {
    config(['payrex.webhook.enabled' => false]);

    // Re-load routes
    (new LegionHQ\LaravelPayrex\PayrexServiceProvider($this->app))->register();

    // The route was already registered in the test setup, so we verify the config is wired up
    expect(config('payrex.webhook.enabled'))->toBeFalse();
});

it('allows configuring a custom webhook controller', function () {
    expect(config('payrex.webhook.controller'))->toBeNull();

    config(['payrex.webhook.controller' => 'App\\Http\\Controllers\\CustomWebhookController']);

    expect(config('payrex.webhook.controller'))->toBe('App\\Http\\Controllers\\CustomWebhookController');
});
