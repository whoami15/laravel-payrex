<?php

return [

    /*
     * Your PayRex public API key for client-side integrations (e.g. Elements).
     * This key is safe to expose in frontend code.
     */
    'public_key' => env('PAYREX_PUBLIC_KEY', ''),

    /*
     * Your PayRex secret API key for authenticating backend API requests.
     * Find your keys at: Dashboard > Developers > API Keys
     *
     * Sandbox vs Live: There is no separate sandbox_mode toggle. Your API key
     * determines the environment — use a test key (sk_test_...) for sandbox
     * and a live key (sk_live_...) for production.
     */
    'secret_key' => env('PAYREX_SECRET_KEY', ''),

    /*
     * The base URL for the PayRex API.
     */
    'api_base_url' => env('PAYREX_API_BASE_URL', 'https://api.payrexhq.com'),

    /*
     * Default currency. PayRex currently only supports PHP (Philippine Peso).
     */
    'currency' => env('PAYREX_CURRENCY', 'PHP'),

    /*
     * HTTP request and connection timeouts in seconds.
     */
    'timeout' => env('PAYREX_TIMEOUT', 30),
    'connect_timeout' => env('PAYREX_CONNECT_TIMEOUT', 30),

    /*
     * Automatic retry configuration for server errors (5xx).
     * Client errors (4xx) are never retried.
     *
     * retries     - Number of retry attempts (0 = disabled).
     * retry_delay - Delay between retries in milliseconds.
     */
    'retries' => env('PAYREX_RETRIES', 0),
    'retry_delay' => env('PAYREX_RETRY_DELAY', 100),

    /*
     * Webhook configuration.
     *
     * enabled    - Whether to register the webhook route.
     * secret     - Your webhook's secret key for signature verification.
     * path       - The URI path where PayRex will send webhook events.
     * tolerance  - Max age in seconds for webhook timestamps (0 = disabled).
     *
     * CSRF Exclusion (Laravel 11+):
     * If you define the webhook route manually, exclude it from CSRF verification:
     *
     *     ->withMiddleware(function (Middleware $middleware) {
     *         $middleware->validateCsrfTokens(except: ['payrex/webhook']);
     *     })
     */
    'webhook' => [
        'enabled' => env('PAYREX_WEBHOOK_ENABLED', true),
        'secret' => env('PAYREX_WEBHOOK_SECRET', ''),
        'path' => env('PAYREX_WEBHOOK_PATH', 'payrex/webhook'),
        'tolerance' => env('PAYREX_WEBHOOK_TOLERANCE', 300),
    ],

];
