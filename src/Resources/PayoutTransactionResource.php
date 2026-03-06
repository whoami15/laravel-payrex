<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Data\PayoutTransaction;
use LegionHQ\LaravelPayrex\Data\PayrexCollection;

class PayoutTransactionResource extends ApiResource
{
    protected function resourceUri(): string
    {
        return '/payouts';
    }

    /** @param  array{limit?: int, before?: string, after?: string}  $params */
    public function list(string $payoutId, array $params = []): PayrexCollection
    {
        return new PayrexCollection(
            $this->client->get("{$this->resourceUri()}/{$payoutId}/transactions", $params),
            PayoutTransaction::class,
            fn (array $pagination) => $this->list($payoutId, array_merge($params, $pagination)),
        );
    }
}
