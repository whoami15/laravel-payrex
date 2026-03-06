<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex;

use LegionHQ\LaravelPayrex\Events\PayrexEvent;
use LegionHQ\LaravelPayrex\Exceptions\WebhookVerificationException;

class PayrexWebhook
{
    public const DEFAULT_TOLERANCE = 300;

    /**
     * Verify the webhook signature and construct a PayrexEvent from the payload.
     *
     * @throws WebhookVerificationException
     */
    public static function constructEvent(
        string $payload,
        string $signatureHeader,
        string $secret,
        int $tolerance = self::DEFAULT_TOLERANCE,
    ): PayrexEvent {
        WebhookSignature::verifyHeader($payload, $signatureHeader, $secret, $tolerance);

        $data = json_decode($payload, true);

        if (! is_array($data)) {
            throw WebhookVerificationException::invalidPayload();
        }

        return PayrexEvent::constructFrom($data);
    }
}
