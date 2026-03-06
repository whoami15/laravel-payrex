<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Exceptions;

use Exception;

class PayrexApiException extends Exception
{
    /**
     * @param  array<int, array<string, mixed>>  $errors
     * @param  array<string, mixed>  $body
     */
    public function __construct(
        string $message = '',
        public readonly array $errors = [],
        public readonly int $statusCode = 0,
        public readonly array $body = [],
    ) {
        parent::__construct($message, $statusCode);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    public static function fromResponse(array $body, int $statusCode = 0): static
    {
        $errors = $body['errors'] ?? [];
        $message = ! empty($errors)
            ? $errors[0]['detail'] ?? 'An API error occurred.'
            : 'An API error occurred.';

        return new static(
            message: $message,
            errors: $errors,
            statusCode: $statusCode,
            body: $body,
        );
    }
}
