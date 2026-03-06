<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyWebhookSignature
{
    public function __construct(
        private readonly string $webhookSecret,
        private readonly int $tolerance = 300,
    ) {}

    /** @param Closure(Request): Response $next */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('Payrex-Signature');

        if (! $signature) {
            throw new AccessDeniedHttpException('Missing Payrex-Signature header.');
        }

        if (! $this->isValidSignature($request->getContent(), $signature)) {
            throw new AccessDeniedHttpException('Invalid webhook signature.');
        }

        return $next($request);
    }

    protected function isValidSignature(string $payload, string $signature): bool
    {
        $parts = explode(', ', $signature);

        if (count($parts) < 3) {
            return false;
        }

        $timestamp = $this->extractValue($parts[0]);
        $testSignature = $this->extractValue($parts[1]);
        $liveSignature = $this->extractValue($parts[2]);

        if ($this->tolerance > 0) {
            if ((time() - (int) $timestamp) > $this->tolerance) {
                return false;
            }
        }

        $actualSignature = ! empty($liveSignature) ? $liveSignature : $testSignature;

        $expectedSignature = hash_hmac('sha256', $timestamp.'.'.$payload, $this->webhookSecret);

        return hash_equals($expectedSignature, $actualSignature);
    }

    protected function extractValue(string $part): string
    {
        $segments = explode('=', $part, 2);

        return $segments[1] ?? '';
    }
}
