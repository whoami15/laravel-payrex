<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Concerns;

use LegionHQ\LaravelPayrex\Data\PayrexCollection;
use LegionHQ\LaravelPayrex\Data\PayrexObject;

/**
 * @mixin \LegionHQ\LaravelPayrex\Resources\ApiResource
 */
trait HasList
{
    /** @return class-string<PayrexObject> */
    abstract protected function listItemClass(): string;

    /** @param  array{limit?: int, before?: string, after?: string}  $params */
    public function list(array $params = []): PayrexCollection
    {
        return new PayrexCollection(
            $this->client->get($this->resourceUri(), $params),
            $this->listItemClass(),
            fn (array $pagination) => $this->list(array_merge($params, $pagination)),
        );
    }
}
