<?php

declare(strict_types=1);

use LegionHQ\LaravelPayrex\Exceptions\WebhookVerificationException;
use LegionHQ\LaravelPayrex\WebhookSignature;

function buildSignature(string $payload, string $secret, ?int $timestamp = null, bool $useLive = false): string
{
    $timestamp = $timestamp ?? time();
    $signature = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

    if ($useLive) {
        return "t={$timestamp}, te=, li={$signature}";
    }

    return "t={$timestamp}, te={$signature}, li=";
}

it('verifies a valid test signature', function () {
    $payload = '{"type":"test"}';
    $header = buildSignature($payload, 'whsec_test');

    WebhookSignature::verifyHeader($payload, $header, 'whsec_test');
})->throwsNoExceptions();

it('verifies a valid live signature', function () {
    $payload = '{"type":"live"}';
    $header = buildSignature($payload, 'whsec_live', useLive: true);

    WebhookSignature::verifyHeader($payload, $header, 'whsec_live');
})->throwsNoExceptions();

it('throws on invalid header format', function () {
    WebhookSignature::verifyHeader('{}', 'invalid-header-no-timestamp', 'whsec_test');
})->throws(WebhookVerificationException::class, 'Unable to parse Payrex-Signature header.');

it('throws on tampered signature', function () {
    $timestamp = time();
    $header = "t={$timestamp}, te=tampered, li=";

    WebhookSignature::verifyHeader('{"type":"test"}', $header, 'whsec_test');
})->throws(WebhookVerificationException::class, 'Webhook signature does not match the expected signature.');

it('throws on expired timestamp', function () {
    $payload = '{"type":"test"}';
    $expiredTimestamp = time() - 600;
    $header = buildSignature($payload, 'whsec_test', $expiredTimestamp);

    WebhookSignature::verifyHeader($payload, $header, 'whsec_test', tolerance: 300);
})->throws(WebhookVerificationException::class, 'Webhook timestamp is outside the tolerance zone.');

it('skips timestamp check when tolerance is zero', function () {
    $payload = '{"type":"test"}';
    $oldTimestamp = 1000000;
    $header = buildSignature($payload, 'whsec_test', $oldTimestamp);

    WebhookSignature::verifyHeader($payload, $header, 'whsec_test', tolerance: 0);
})->throwsNoExceptions();
