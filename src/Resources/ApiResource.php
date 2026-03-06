<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\PayrexClient;

abstract class ApiResource
{
    public function __construct(
        protected PayrexClient $client,
    ) {}

    abstract protected function resourceUri(): string;

    /** @param  array<string, mixed>  $params */
    protected function withDefaultCurrency(array $params): array
    {
        $params['currency'] ??= config('payrex.currency', 'PHP');

        return $params;
    }
}
