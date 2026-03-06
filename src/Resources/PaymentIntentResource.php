<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Data\PaymentIntent;

class PaymentIntentResource extends ApiResource
{
    protected function resourceUri(): string
    {
        return '/payment_intents';
    }

    /**
     * @param  array{
     *     amount: int,
     *     currency?: string,
     *     payment_methods: array<string>,
     *     description?: string,
     *     statement_descriptor?: string,
     *     metadata?: array<string, string>,
     *     return_url?: string,
     *     customer_id?: string,
     *     payment_method_options?: array{
     *         card?: array{
     *             capture_type?: string,
     *             allowed_bins?: array<string>,
     *             allowed_funding?: array<string>,
     *         },
     *     },
     * }  $params
     */
    public function create(array $params): PaymentIntent
    {
        return new PaymentIntent($this->client->post($this->resourceUri(), $this->withDefaultCurrency($params)));
    }

    public function retrieve(string $id): PaymentIntent
    {
        return new PaymentIntent($this->client->get("{$this->resourceUri()}/{$id}"));
    }

    public function cancel(string $id): PaymentIntent
    {
        return new PaymentIntent($this->client->post("{$this->resourceUri()}/{$id}/cancel"));
    }

    /** @param  array{amount?: int}  $params */
    public function capture(string $id, array $params = []): PaymentIntent
    {
        return new PaymentIntent($this->client->post("{$this->resourceUri()}/{$id}/capture", $params));
    }
}
