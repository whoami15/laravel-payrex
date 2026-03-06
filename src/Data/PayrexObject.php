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
    /** @var array<string, class-string<PayrexObject>> */
    protected const RESOURCE_MAP = [
        'payment_intent' => PaymentIntent::class,
        'payment' => Payment::class,
        'checkout_session' => CheckoutSession::class,
        'refund' => Refund::class,
        'customer' => Customer::class,
        'billing_statement' => BillingStatement::class,
        'billing_statement_line_item' => BillingStatementLineItem::class,
        'payout' => Payout::class,
        'payout_transaction' => PayoutTransaction::class,
        'webhook' => WebhookEndpoint::class,
    ];

    public readonly string $id;

    public readonly string $resource;

    public readonly bool $livemode;

    /** @var array<string, string>|null */
    public readonly ?array $metadata;

    public readonly ?int $createdAt;

    public readonly ?int $updatedAt;

    /**
     * Build the appropriate PayrexObject subclass from raw attributes.
     *
     * Uses the `resource` key to resolve to a specific class (e.g. PaymentIntent),
     * falling back to a generic PayrexObject when the type is unknown.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function constructFrom(array $attributes): self
    {
        $attributes['id'] ??= '';
        $class = static::RESOURCE_MAP[$attributes['resource'] ?? ''] ?? static::class;

        return new $class($attributes);
    }

    /** @param array<string, mixed> $attributes */
    public function __construct(
        protected readonly array $attributes,
    ) {
        $this->id = $this->attributes['id'] ?? throw new \InvalidArgumentException('Missing required field: id');
        $this->resource = $this->attributes['resource'] ?? '';
        $this->livemode = $this->attributes['livemode'] ?? false;
        $this->metadata = $this->attributes['metadata'] ?? null;
        $this->createdAt = $this->attributes['created_at'] ?? null;
        $this->updatedAt = $this->attributes['updated_at'] ?? null;
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
        throw new \LogicException('PayrexObject is immutable.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \LogicException('PayrexObject is immutable.');
    }
}
