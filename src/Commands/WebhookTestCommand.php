<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use LegionHQ\LaravelPayrex\Enums\WebhookEventType;
use LegionHQ\LaravelPayrex\Events\PayrexWebhookReceived;

class WebhookTestCommand extends Command
{
    protected $signature = 'payrex:webhook-test {type : The event type to simulate (e.g. payment_intent.succeeded)}';

    protected $description = 'Dispatch a synthetic webhook event locally for testing listeners';

    public function handle(): int
    {
        $type = $this->argument('type');

        $valid = array_column(WebhookEventType::cases(), 'value');

        if (! in_array($type, $valid, true)) {
            $this->error("Invalid event type: {$type}");
            $this->line('Valid types: '.implode(', ', $valid));

            return self::FAILURE;
        }

        $payload = [
            'id' => 'evt_test_'.Str::random(8),
            'resource' => 'event',
            'type' => $type,
            'livemode' => false,
            'pending_webhooks' => 0,
            'data' => [
                'resource' => [
                    'id' => 'res_test_'.Str::random(8),
                    'resource' => Str::before($type, '.'),
                ],
            ],
            'created_at' => time(),
            'updated_at' => time(),
        ];

        PayrexWebhookReceived::dispatch($payload);

        $eventClass = $this->resolveEventClass($type);

        if (class_exists($eventClass)) {
            $eventClass::dispatch($payload);
        }

        $this->info("Dispatched {$type} event successfully.");

        return self::SUCCESS;
    }

    /** @return class-string */
    protected function resolveEventClass(string $eventType): string
    {
        return 'LegionHQ\\LaravelPayrex\\Events\\'.Str::of($eventType)
            ->replace('.', ' ')
            ->replace('_', ' ')
            ->title()
            ->replace(' ', '');
    }
}
