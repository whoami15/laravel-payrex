<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Data\Payment;

class PaymentResource extends ApiResource
{
    protected function resourceUri(): string
    {
        return '/payments';
    }

    public function retrieve(string $id): Payment
    {
        return new Payment($this->client->get("{$this->resourceUri()}/{$id}"));
    }

    /**
     * @param  array{
     *     description?: string,
     *     metadata?: array<string, string>,
     * }  $params
     */
    public function update(string $id, array $params): Payment
    {
        return new Payment($this->client->put("{$this->resourceUri()}/{$id}", $params));
    }
}
