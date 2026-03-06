<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Concerns\HasList;
use LegionHQ\LaravelPayrex\Data\Customer;
use LegionHQ\LaravelPayrex\Data\PayrexObject;

class CustomerResource extends ApiResource
{
    use HasList;

    protected function resourceUri(): string
    {
        return '/customers';
    }

    protected function listItemClass(): string
    {
        return Customer::class;
    }

    /**
     * @param  array{
     *     name: string,
     *     email: string,
     *     currency?: string,
     *     billing_details?: array{
     *         phone?: string,
     *         address?: array{
     *             line1?: string,
     *             line2?: string,
     *             city?: string,
     *             state?: string,
     *             postal_code?: string,
     *             country?: string,
     *         },
     *     },
     *     billing_statement_prefix?: string,
     *     next_billing_statement_sequence_number?: int,
     *     metadata?: array<string, string>,
     * }  $params
     */
    public function create(array $params): Customer
    {
        return new Customer($this->client->post($this->resourceUri(), $this->withDefaultCurrency($params)));
    }

    public function retrieve(string $id): Customer
    {
        return new Customer($this->client->get("{$this->resourceUri()}/{$id}"));
    }

    /**
     * @param  array{
     *     name?: string,
     *     email?: string,
     *     currency?: string,
     *     billing_details?: array{
     *         phone?: string,
     *         address?: array{
     *             line1?: string,
     *             line2?: string,
     *             city?: string,
     *             state?: string,
     *             postal_code?: string,
     *             country?: string,
     *         },
     *     },
     *     billing_statement_prefix?: string,
     *     next_billing_statement_sequence_number?: int,
     *     metadata?: array<string, string>,
     * }  $params
     */
    public function update(string $id, array $params): Customer
    {
        return new Customer($this->client->put("{$this->resourceUri()}/{$id}", $params));
    }

    public function delete(string $id): PayrexObject
    {
        return new PayrexObject($this->client->delete("{$this->resourceUri()}/{$id}"));
    }
}
