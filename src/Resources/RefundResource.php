<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Data\Refund;
use LegionHQ\LaravelPayrex\Exceptions\PayrexApiException;

class RefundResource extends ApiResource
{
    protected function resourceUri(): string
    {
        return '/refunds';
    }

    /**
     * @param  array{
     *     payment_id: string,
     *     amount: int,
     *     currency?: string,
     *     reason: string,
     *     description?: string,
     *     remarks?: string,
     *     metadata?: array<string, string>,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function create(array $params): Refund
    {
        return new Refund($this->client->post($this->resourceUri(), $this->withDefaultCurrency($params)));
    }

    /**
     * @param  array{
     *     description?: string,
     *     remarks?: string,
     *     metadata?: array<string, string>,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function update(string $id, array $params): Refund
    {
        return new Refund($this->client->put("{$this->resourceUri()}/{$id}", $params));
    }
}
