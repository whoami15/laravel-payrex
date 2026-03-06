<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Middleware;

use Closure;
use Illuminate\Http\Request;
use LegionHQ\LaravelPayrex\Exceptions\WebhookVerificationException;
use LegionHQ\LaravelPayrex\WebhookSignature;
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

        try {
            WebhookSignature::verifyHeader(
                $request->getContent(),
                $signature,
                $this->webhookSecret,
                $this->tolerance,
            );
        } catch (WebhookVerificationException $e) {
            throw new AccessDeniedHttpException($e->getMessage(), $e);
        }

        return $next($request);
    }
}
