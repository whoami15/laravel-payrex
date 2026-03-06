<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Data\BillingStatementLineItem;
use LegionHQ\LaravelPayrex\Data\PayrexObject;
use LegionHQ\LaravelPayrex\Exceptions\PayrexApiException;

class BillingStatementLineItemResource extends ApiResource
{
    protected function resourceUri(): string
    {
        return '/billing_statement_line_items';
    }

    /**
     * @param  array{
     *     billing_statement_id: string,
     *     description: string,
     *     unit_price: int,
     *     quantity: int,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function create(array $params): BillingStatementLineItem
    {
        return new BillingStatementLineItem($this->client->post($this->resourceUri(), $params));
    }

    /**
     * @param  array{
     *     description?: string,
     *     unit_price?: int,
     *     quantity?: int,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function update(string $id, array $params): BillingStatementLineItem
    {
        return new BillingStatementLineItem($this->client->put("{$this->resourceUri()}/{$id}", $params));
    }

    /** @throws PayrexApiException */
    public function delete(string $id): PayrexObject
    {
        return new PayrexObject($this->client->delete("{$this->resourceUri()}/{$id}"));
    }
}
