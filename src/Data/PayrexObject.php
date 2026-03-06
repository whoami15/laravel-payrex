<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Data;

use ArrayAccess;
use JsonSerializable;

/**
 * @implements ArrayAccess<string, mixed>
 */
class PayrexObject implements ArrayAccess, JsonSerializable
{
    public readonly ?string $id;

    public readonly ?string $resource;

    public readonly ?bool $livemode;

    /** @var array<string, string>|null */
    public readonly ?array $metadata;

    public readonly ?int $createdAt;

    public readonly ?int $updatedAt;

    /** @param array<string, mixed> $attributes */
    public function __construct(
        protected readonly array $attributes = [],
    ) {
        $this->id = $this->attributes['id'] ?? null;
        $this->resource = $this->attributes['resource'] ?? null;
        $this->livemode = $this->attributes['livemode'] ?? null;
        $this->metadata = $this->attributes['metadata'] ?? null;
        $this->createdAt = $this->attributes['created_at'] ?? null;
        $this->updatedAt = $this->attributes['updated_at'] ?? null;
    }

    /** @param array<string, mixed> $attributes */
    public static function from(array $attributes): static
    {
        return new static($attributes); // @phpstan-ignore new.static
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->attributes;
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
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Immutable
    }

    public function offsetUnset(mixed $offset): void
    {
        // Immutable
    }
}
