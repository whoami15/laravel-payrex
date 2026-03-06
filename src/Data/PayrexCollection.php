<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use Illuminate\Support\LazyCollection;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @template-covariant T of PayrexObject
 *
 * @implements ArrayAccess<string, mixed>
 * @implements IteratorAggregate<int, T>
 */
class PayrexCollection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    public readonly ?string $resource;

    public readonly bool $hasMore;

    /** @var array<int, T> */
    public readonly array $data;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  class-string<T>  $itemClass
     * @param  (Closure(array<string, string>): PayrexCollection<T>)|null  $paginator
     */
    public function __construct(
        protected readonly array $attributes,
        protected readonly string $itemClass,
        protected readonly ?Closure $paginator = null,
    ) {
        $this->resource = $attributes['resource'] ?? null;
        $this->hasMore = $attributes['has_more'] ?? false;
        $this->data = array_map(
            fn (array $item): PayrexObject => new $this->itemClass($item),
            $attributes['data'] ?? [],
        );
    }

    /** @return LazyCollection<int, covariant T> */
    public function autoPaginate(): LazyCollection
    {
        return new LazyCollection(function () {
            $collection = $this;

            while (true) {
                foreach ($collection->data as $item) {
                    yield $item;
                }

                if (! $collection->hasMore || empty($collection->data) || $collection->paginator === null) {
                    break;
                }

                $data = $collection->data;
                $lastItem = end($data);
                $collection = ($collection->paginator)(['after' => $lastItem->id]);
            }
        });
    }

    public function count(): int
    {
        return count($this->data);
    }

    /** @return Traversable<int, T> */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        if ($offset === 'data') {
            return $this->data;
        }

        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \LogicException('PayrexCollection is immutable.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \LogicException('PayrexCollection is immutable.');
    }
}
