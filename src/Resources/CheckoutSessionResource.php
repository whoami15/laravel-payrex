<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Data\CheckoutSession;
use LegionHQ\LaravelPayrex\Exceptions\PayrexApiException;

class CheckoutSessionResource extends ApiResource
{
    protected function resourceUri(): string
    {
        return '/checkout_sessions';
    }

    /**
     * @param  array{
     *     currency?: string,
     *     line_items: array<array{
     *         name: string,
     *         amount: int,
     *         quantity: int,
     *         description?: string,
     *         image?: string,
     *     }>,
     *     success_url: string,
     *     cancel_url: string,
     *     payment_methods?: array<string>,
     *     customer_reference_id?: string,
     *     description?: string,
     *     expires_at?: int,
     *     billing_details_collection?: string,
     *     submit_type?: string,
     *     statement_descriptor?: string,
     *     payment_method_options?: array<string, mixed>,
     *     metadata?: array<string, string>,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function create(array $params): CheckoutSession
    {
        return new CheckoutSession($this->client->post($this->resourceUri(), $this->withDefaultCurrency($params)));
    }

    /** @throws PayrexApiException */
    public function retrieve(string $id): CheckoutSession
    {
        return new CheckoutSession($this->client->get("{$this->resourceUri()}/{$id}"));
    }

    /** @throws PayrexApiException */
    public function expire(string $id): CheckoutSession
    {
        return new CheckoutSession($this->client->post("{$this->resourceUri()}/{$id}/expire"));
    }
}
