<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Commands;

use Illuminate\Console\Command;
use LegionHQ\LaravelPayrex\Data\WebhookEndpoint;
use LegionHQ\LaravelPayrex\PayrexClient;

class WebhookListCommand extends Command
{
    protected $signature = 'payrex:webhook-list';

    protected $description = 'List all PayRex webhook endpoints';

    public function handle(PayrexClient $client): int
    {
        $collection = $client->webhooks->list();

        if (count($collection) === 0) {
            $this->info('No webhook endpoints found.');

            return self::SUCCESS;
        }

        $rows = [];

        foreach ($collection->data as $item) {
            /** @var WebhookEndpoint $webhook */
            $webhook = $item;

            $rows[] = [
                $webhook->id,
                $webhook->url,
                $webhook->status,
                implode(', ', $webhook->events ?? []),
                $webhook->createdAt ? date('Y-m-d H:i:s', $webhook->createdAt) : '-',
            ];
        }

        $this->table(['ID', 'URL', 'Status', 'Events', 'Created At'], $rows);

        return self::SUCCESS;
    }
}
