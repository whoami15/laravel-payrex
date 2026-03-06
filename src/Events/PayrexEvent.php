<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Events;

use Illuminate\Foundation\Events\Dispatchable;

abstract class PayrexEvent
{
    use Dispatchable;

    /** @param array<string, mixed> $payload */
    public function __construct(
        public readonly array $payload,
    ) {}

    /** @return array<string, mixed> */
    public function data(): array
    {
        return $this->payload['data']['resource'] ?? [];
    }

    public function eventType(): ?string
    {
        return $this->payload['type'] ?? null;
    }

    public function isLiveMode(): bool
    {
        return ($this->payload['livemode'] ?? false) === true;
    }
}
