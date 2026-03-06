<?php

declare(strict_types=1);

arch('all events extend PayrexEvent')
    ->expect('LegionHQ\LaravelPayrex\Events')
    ->classes()
    ->toExtend('LegionHQ\LaravelPayrex\Events\PayrexEvent');

arch('all api exceptions extend PayrexApiException except WebhookVerificationException')
    ->expect('LegionHQ\LaravelPayrex\Exceptions')
    ->classes()
    ->toExtend('LegionHQ\LaravelPayrex\Exceptions\PayrexApiException')
    ->ignoring('LegionHQ\LaravelPayrex\Exceptions\WebhookVerificationException')
    ->ignoring('LegionHQ\LaravelPayrex\Exceptions\PayrexException');

arch('all resources extend ApiResource')
    ->expect('LegionHQ\LaravelPayrex\Resources')
    ->classes()
    ->toExtend('LegionHQ\LaravelPayrex\Resources\ApiResource');

arch('enums are string-backed')
    ->expect('LegionHQ\LaravelPayrex\Enums')
    ->toBeStringBackedEnums();

arch('no debugging functions in source code')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->not->toBeUsed();

arch('PayrexEvent is abstract')
    ->expect('LegionHQ\LaravelPayrex\Events\PayrexEvent')
    ->toBeAbstract();

arch('ApiResource is abstract')
    ->expect('LegionHQ\LaravelPayrex\Resources\ApiResource')
    ->toBeAbstract();
