<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Events;

/** Dispatched when a billing statement is paid by the customer. */
class BillingStatementPaid extends PayrexEvent {}
