<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Events;

use Illuminate\Foundation\Events\Dispatchable;
use LegionHQ\LaravelPayrex\Data\PayrexObject;
use LegionHQ\LaravelPayrex\Enums\WebhookEventType;

abstract class PayrexEvent
{
    use Dispatchable;

    /** @var array<string, class-string<PayrexEvent>> */
    private const EVENT_MAP = [
        'payment_intent.succeeded' => PaymentIntentSucceeded::class,
        'payment_intent.awaiting_capture' => PaymentIntentAwaitingCapture::class,
        'checkout_session.expired' => CheckoutSessionExpired::class,
        'payout.deposited' => PayoutDeposited::class,
        'refund.created' => RefundCreated::class,
        'refund.updated' => RefundUpdated::class,
        'billing_statement.created' => BillingStatementCreated::class,
        'billing_statement.updated' => BillingStatementUpdated::class,
        'billing_statement.deleted' => BillingStatementDeleted::class,
        'billing_statement.finalized' => BillingStatementFinalized::class,
        'billing_statement.sent' => BillingStatementSent::class,
        'billing_statement.marked_uncollectible' => BillingStatementMarkedUncollectible::class,
        'billing_statement.voided' => BillingStatementVoided::class,
        'billing_statement.paid' => BillingStatementPaid::class,
        'billing_statement.will_be_due' => BillingStatementWillBeDue::class,
        'billing_statement.overdue' => BillingStatementOverdue::class,
        'billing_statement_line_item.created' => BillingStatementLineItemCreated::class,
        'billing_statement_line_item.updated' => BillingStatementLineItemUpdated::class,
        'billing_statement_line_item.deleted' => BillingStatementLineItemDeleted::class,
    ];

    /** @param array<string, mixed> $payload */
    public function __construct(
        public readonly array $payload,
    ) {}

    /**
     * Build the appropriate event instance from a decoded webhook payload.
     *
     * Resolves to the specific event class (e.g. PaymentIntentSucceeded) when
     * it exists, otherwise falls back to PayrexWebhookReceived.
     *
     * @param  array<string, mixed>  $data
     */
    public static function constructFrom(array $data): self
    {
        $eventClass = self::resolveEventClass($data['type'] ?? '');

        if ($eventClass && class_exists($eventClass)) {
            return new $eventClass($data);
        }

        return new PayrexWebhookReceived($data);
    }

    /**
     * Dispatch the generic PayrexWebhookReceived event, then the typed event
     * for the specific event type if one exists.
     *
     * @param  array<string, mixed>  $payload
     */
    public static function dispatchWebhook(array $payload): void
    {
        PayrexWebhookReceived::dispatch($payload);

        $eventType = $payload['type'] ?? null;

        if ($eventType) {
            $eventClass = static::resolveEventClass($eventType);

            if ($eventClass && class_exists($eventClass)) {
                $eventClass::dispatch($payload);
            }
        }
    }

    /**
     * Resolve a webhook event type string to its corresponding event class.
     *
     * @return class-string<PayrexEvent>|null
     */
    public static function resolveEventClass(string $eventType): ?string
    {
        return self::EVENT_MAP[$eventType] ?? null;
    }

    /**
     * The affected resource as a typed DTO with enum casting.
     */
    public function data(): PayrexObject
    {
        $resource = $this->payload['data']['resource'] ?? [];

        return PayrexObject::constructFrom($resource);
    }

    public function eventType(): ?WebhookEventType
    {
        $type = $this->payload['type'] ?? null;

        if ($type === null) {
            return null;
        }

        return WebhookEventType::tryFrom($type);
    }

    public function isLiveMode(): bool
    {
        return ($this->payload['livemode'] ?? false) === true;
    }
}
