<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Resources;

use LegionHQ\LaravelPayrex\Concerns\HasList;
use LegionHQ\LaravelPayrex\Data\PayrexObject;
use LegionHQ\LaravelPayrex\Data\WebhookEndpoint;

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
     */
    public function create(array $params): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->post($this->resourceUri(), $params));
    }

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
     */
    public function update(string $id, array $params): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->put("{$this->resourceUri()}/{$id}", $params));
    }

    public function delete(string $id): PayrexObject
    {
        return new PayrexObject($this->client->delete("{$this->resourceUri()}/{$id}"));
    }

    public function enable(string $id): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->post("{$this->resourceUri()}/{$id}/enable"));
    }

    public function disable(string $id): WebhookEndpoint
    {
        return new WebhookEndpoint($this->client->post("{$this->resourceUri()}/{$id}/disable"));
    }
}
