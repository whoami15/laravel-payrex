<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use LegionHQ\LaravelPayrex\Events\PayrexWebhookReceived;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);

        if (! is_array($payload)) {
            return new Response('Invalid JSON payload', 400);
        }

        PayrexWebhookReceived::dispatch($payload);

        $eventType = $payload['type'] ?? null;

        if ($eventType) {
            $eventClass = $this->resolveEventClass($eventType);

            if (class_exists($eventClass)) {
                $eventClass::dispatch($payload);
            }
        }

        return new Response('Webhook handled', 200);
    }

    /** @return class-string */
    protected function resolveEventClass(string $eventType): string
    {
        return 'LegionHQ\\LaravelPayrex\\Events\\'.Str::of($eventType)
            ->replace('.', ' ')
            ->replace('_', ' ')
            ->title()
            ->replace(' ', '');
    }
}
