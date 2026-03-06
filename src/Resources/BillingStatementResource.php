<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Concerns\HasList;
use LegionHQ\LaravelPayrex\Data\BillingStatement;
use LegionHQ\LaravelPayrex\Data\PayrexCollection;
use LegionHQ\LaravelPayrex\Data\PayrexObject;
use LegionHQ\LaravelPayrex\Exceptions\PayrexApiException;

/**
 * @method PayrexCollection<BillingStatement> list(array<string, mixed> $params = [])
 */
class BillingStatementResource extends ApiResource
{
    use HasList;

    protected function resourceUri(): string
    {
        return '/billing_statements';
    }

    protected function listItemClass(): string
    {
        return BillingStatement::class;
    }

    /**
     * @param  array{
     *     customer_id: string,
     *     currency?: string,
     *     description?: string,
     *     due_at?: int,
     *     billing_details_collection?: string,
     *     metadata?: array<string, string>,
     *     payment_settings?: array{
     *         payment_methods?: array<string>,
     *         payment_method_options?: array{
     *             card?: array{
     *                 allowed_bins?: array<string>,
     *                 allowed_funding?: array<string>,
     *             },
     *         },
     *     },
     *     line_items?: array<array{
     *         description: string,
     *         unit_price: int,
     *         quantity: int,
     *     }>,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function create(array $params): BillingStatement
    {
        return new BillingStatement($this->client->post($this->resourceUri(), $this->withDefaultCurrency($params)));
    }

    /** @throws PayrexApiException */
    public function retrieve(string $id): BillingStatement
    {
        return new BillingStatement($this->client->get("{$this->resourceUri()}/{$id}"));
    }

    /**
     * @param  array{
     *     customer_id?: string,
     *     description?: string,
     *     due_at?: int,
     *     billing_details_collection?: string,
     *     metadata?: array<string, string>,
     *     payment_settings?: array{
     *         payment_methods?: array<string>,
     *         payment_method_options?: array<string, mixed>,
     *     },
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function update(string $id, array $params): BillingStatement
    {
        return new BillingStatement($this->client->put("{$this->resourceUri()}/{$id}", $params));
    }

    /** @throws PayrexApiException */
    public function delete(string $id): PayrexObject
    {
        return new PayrexObject($this->client->delete("{$this->resourceUri()}/{$id}"));
    }

    /** @throws PayrexApiException */
    public function finalize(string $id): BillingStatement
    {
        return new BillingStatement($this->client->post("{$this->resourceUri()}/{$id}/finalize"));
    }

    /** @throws PayrexApiException */
    public function void(string $id): BillingStatement
    {
        return new BillingStatement($this->client->post("{$this->resourceUri()}/{$id}/void"));
    }

    /** @throws PayrexApiException */
    public function markUncollectible(string $id): BillingStatement
    {
        return new BillingStatement($this->client->post("{$this->resourceUri()}/{$id}/mark_uncollectible"));
    }

    /** @throws PayrexApiException */
    public function send(string $id): BillingStatement
    {
        return new BillingStatement($this->client->post("{$this->resourceUri()}/{$id}/send"));
    }
}
