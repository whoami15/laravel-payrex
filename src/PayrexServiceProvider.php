<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex;

use LegionHQ\LaravelPayrex\Commands\WebhookListCommand;
use LegionHQ\LaravelPayrex\Commands\WebhookTestCommand;
use LegionHQ\LaravelPayrex\Middleware\VerifyWebhookSignature;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PayrexServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('payrex')
            ->hasConfigFile()
            ->hasRoute('webhook')
            ->hasCommands([
                WebhookListCommand::class,
                WebhookTestCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(PayrexClient::class, function () {
            return new PayrexClient(
                secretKey: config('payrex.secret_key'),
                baseUrl: config('payrex.api_base_url', 'https://api.payrexhq.com'),
                timeout: (int) config('payrex.timeout', 30),
                connectTimeout: (int) config('payrex.connect_timeout', 30),
                retries: (int) config('payrex.retries', 0),
                retryDelay: (int) config('payrex.retry_delay', 100),
            );
        });

        $this->app->alias(PayrexClient::class, 'payrex');

        $this->app->singleton(VerifyWebhookSignature::class, function () {
            return new VerifyWebhookSignature(
                webhookSecret: config('payrex.webhook.secret', ''),
                tolerance: (int) config('payrex.webhook.tolerance', 300),
            );
        });
    }
}
