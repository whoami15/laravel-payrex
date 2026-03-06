<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex;

use LegionHQ\LaravelPayrex\Exceptions\WebhookVerificationException;

class WebhookSignature
{
    public static function verifyHeader(
        string $payload,
        string $signatureHeader,
        string $secret,
        int $tolerance = PayrexWebhook::DEFAULT_TOLERANCE,
    ): void {
        $parts = explode(', ', $signatureHeader);

        $parsed = [];
        foreach ($parts as $part) {
            $segments = explode('=', $part, 2);
            if (count($segments) === 2) {
                $parsed[trim($segments[0])] = $segments[1];
            }
        }

        $timestamp = $parsed['t'] ?? throw WebhookVerificationException::invalidHeader();
        $testSignature = $parsed['te'] ?? '';
        $liveSignature = $parsed['li'] ?? '';

        if ($tolerance > 0 && (time() - (int) $timestamp) > $tolerance) {
            throw WebhookVerificationException::timestampOutsideTolerance();
        }

        $actualSignature = $liveSignature !== '' ? $liveSignature : $testSignature;

        $expectedSignature = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        if (! hash_equals($expectedSignature, $actualSignature)) {
            throw WebhookVerificationException::invalidSignature();
        }
    }
}
