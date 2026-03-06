<?php

use Illuminate\Support\Facades\Route;
use LegionHQ\LaravelPayrex\Http\Controllers\WebhookController;
use LegionHQ\LaravelPayrex\Middleware\VerifyWebhookSignature;

if (config('payrex.webhook.enabled', true) === false) {
    return;
}

Route::post(config('payrex.webhook.path', 'payrex/webhook'), WebhookController::class)
    ->middleware(VerifyWebhookSignature::class)
    ->name('payrex.webhook');
