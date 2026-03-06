<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use LegionHQ\LaravelPayrex\Enums\WebhookEndpointStatus;

class WebhookEndpoint extends PayrexObject
{
    public readonly ?string $secretKey;

    public readonly ?string $url;

    /** @var array<int, string>|null */
    public readonly ?array $events;

    public readonly ?string $description;

    public readonly ?WebhookEndpointStatus $status;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->secretKey = $attributes['secret_key'] ?? null;
        $this->url = $attributes['url'] ?? null;
        $this->events = $attributes['events'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->status = isset($attributes['status']) ? WebhookEndpointStatus::tryFrom($attributes['status']) : null;
    }
}
