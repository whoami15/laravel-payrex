<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use LegionHQ\LaravelPayrex\Events\PayrexEvent;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $payload = json_decode($request->getContent(), true);

        if (! is_array($payload)) {
            return new Response('Invalid JSON payload', 400);
        }

        PayrexEvent::dispatchWebhook($payload);

        return new Response('Webhook handled', 200);
    }
}
