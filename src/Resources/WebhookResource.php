<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Concerns\HasList;
use LegionHQ\LaravelPayrex\Data\PayrexCollection;
use LegionHQ\LaravelPayrex\Data\PayrexObject;
use LegionHQ\LaravelPayrex\Data\WebhookEndpoint;
use LegionHQ\LaravelPayrex\Exceptions\PayrexApiException;

/**
 * @method PayrexCollection<WebhookEndpoint> list(array<string, mixed> $params = [])
 */
class WebhookResource extends ApiResource
{
    use HasList;

    protected function resourceUri(): string
    {
        return '/webhooks';
    }

    protected function listItemClass(): string
    {
        return WebhookEndpoint::class;
    }

    /**
     * @param  array{
     *     url: string,
     *     events: array<string>,
     *     description?: string,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function create(array $params): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->post($this->resourceUri(), $params));
    }

    /** @throws PayrexApiException */
    public function retrieve(string $id): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->get("{$this->resourceUri()}/{$id}"));
    }

    /**
     * @param  array{
     *     url?: string,
     *     events?: array<string>,
     *     description?: string,
     * }  $params
     *
     * @throws PayrexApiException
     */
    public function update(string $id, array $params): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->put("{$this->resourceUri()}/{$id}", $params));
    }

    /** @throws PayrexApiException */
    public function delete(string $id): PayrexObject
    {
        return new PayrexObject($this->client->delete("{$this->resourceUri()}/{$id}"));
    }

    /** @throws PayrexApiException */
    public function enable(string $id): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->post("{$this->resourceUri()}/{$id}/enable"));
    }

    /** @throws PayrexApiException */
    public function disable(string $id): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->post("{$this->resourceUri()}/{$id}/disable"));
    }
}
