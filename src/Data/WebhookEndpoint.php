<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

class WebhookEndpoint extends PayrexObject
{
    public readonly ?string $secretKey;

    public readonly ?string $url;

    /** @var array<int, string>|null */
    public readonly ?array $events;

    public readonly ?string $description;

    public readonly ?string $status;

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->secretKey = $attributes['secret_key'] ?? null;
        $this->url = $attributes['url'] ?? null;
        $this->events = $attributes['events'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->status = $attributes['status'] ?? null;
    }
}
