<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LegionHQ\LaravelPayrex\Exceptions\AuthenticationException;
use LegionHQ\LaravelPayrex\Exceptions\InvalidRequestException;
use LegionHQ\LaravelPayrex\Exceptions\PayrexApiException;
use LegionHQ\LaravelPayrex\Exceptions\ResourceNotFoundException;
use LegionHQ\LaravelPayrex\Resources\BillingStatementLineItemResource;
use LegionHQ\LaravelPayrex\Resources\BillingStatementResource;
use LegionHQ\LaravelPayrex\Resources\CheckoutSessionResource;
use LegionHQ\LaravelPayrex\Resources\CustomerResource;
use LegionHQ\LaravelPayrex\Resources\PaymentIntentResource;
use LegionHQ\LaravelPayrex\Resources\PaymentResource;
use LegionHQ\LaravelPayrex\Resources\PayoutTransactionResource;
use LegionHQ\LaravelPayrex\Resources\RefundResource;
use LegionHQ\LaravelPayrex\Resources\WebhookResource;
use Throwable;

class PayrexClient
{
    public readonly PaymentIntentResource $paymentIntents;

    public readonly PaymentResource $payments;

    public readonly RefundResource $refunds;

    public readonly CustomerResource $customers;

    public readonly CheckoutSessionResource $checkoutSessions;

    public readonly WebhookResource $webhooks;

    public readonly BillingStatementResource $billingStatements;

    public readonly BillingStatementLineItemResource $billingStatementLineItems;

    public readonly PayoutTransactionResource $payoutTransactions;

    public function __construct(
        private readonly string $secretKey,
        private readonly string $baseUrl = 'https://api.payrexhq.com',
        private readonly int $timeout = 30,
        private readonly int $connectTimeout = 30,
        private readonly int $retries = 0,
        private readonly int $retryDelay = 100,
    ) {
        $this->paymentIntents = new PaymentIntentResource($this);
        $this->payments = new PaymentResource($this);
        $this->refunds = new RefundResource($this);
        $this->customers = new CustomerResource($this);
        $this->checkoutSessions = new CheckoutSessionResource($this);
        $this->webhooks = new WebhookResource($this);
        $this->billingStatements = new BillingStatementResource($this);
        $this->billingStatementLineItems = new BillingStatementLineItemResource($this);
        $this->payoutTransactions = new PayoutTransactionResource($this);
    }

    public function paymentIntents(): PaymentIntentResource
    {
        return $this->paymentIntents;
    }

    public function payments(): PaymentResource
    {
        return $this->payments;
    }

    public function refunds(): RefundResource
    {
        return $this->refunds;
    }

    public function customers(): CustomerResource
    {
        return $this->customers;
    }

    public function checkoutSessions(): CheckoutSessionResource
    {
        return $this->checkoutSessions;
    }

    public function webhooks(): WebhookResource
    {
        return $this->webhooks;
    }

    public function billingStatements(): BillingStatementResource
    {
        return $this->billingStatements;
    }

    public function billingStatementLineItems(): BillingStatementLineItemResource
    {
        return $this->billingStatementLineItems;
    }

    public function payoutTransactions(): PayoutTransactionResource
    {
        return $this->payoutTransactions;
    }

    protected function newRequest(): PendingRequest
    {
        $request = Http::baseUrl($this->baseUrl)
            ->withBasicAuth($this->secretKey, '')
            ->asForm()
            ->acceptJson()
            ->timeout($this->timeout)
            ->connectTimeout($this->connectTimeout);

        if ($this->retries > 0) {
            $request->retry(
                $this->retries,
                $this->retryDelay,
                fn (Throwable $exception, PendingRequest $pendingRequest): bool => $exception instanceof RequestException
                    && $exception->response->serverError(),
                throw: false,
            );
        }

        return $request;
    }

    /** @param array<string, mixed> $query */
    public function get(string $uri, array $query = []): array
    {
        return $this->handleResponse($this->newRequest()->get($uri, $query));
    }

    /** @param array<string, mixed> $data */
    public function post(string $uri, array $data = []): array
    {
        return $this->handleResponse($this->newRequest()->post($uri, $data));
    }

    /** @param array<string, mixed> $data */
    public function put(string $uri, array $data = []): array
    {
        return $this->handleResponse($this->newRequest()->put($uri, $data));
    }

    /** @param array<string, mixed> $query */
    public function delete(string $uri, array $query = []): array
    {
        return $this->handleResponse($this->newRequest()->delete($uri, $query));
    }

    protected function handleResponse(Response $response): array
    {
        if ($response->successful()) {
            return $response->json() ?? [];
        }

        $body = $response->json() ?? [];

        $status = $response->status();

        match ($status) {
            401 => throw AuthenticationException::fromResponse($body, $status),
            404 => throw ResourceNotFoundException::fromResponse($body, $status),
            400 => throw InvalidRequestException::fromResponse($body, $status),
            default => throw PayrexApiException::fromResponse($body, $status),
        };
    }
}
