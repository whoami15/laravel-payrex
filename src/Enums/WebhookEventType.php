<?php

declare(strict_types=1);

namespace LegionHQ\LaravelPayrex\Enums;

enum WebhookEventType: string
{
    case PaymentIntentSucceeded = 'payment_intent.succeeded';
    case PaymentIntentAwaitingCapture = 'payment_intent.awaiting_capture';
    case CheckoutSessionExpired = 'checkout_session.expired';
    case PayoutDeposited = 'payout.deposited';
    case RefundCreated = 'refund.created';
    case RefundUpdated = 'refund.updated';
    case BillingStatementCreated = 'billing_statement.created';
    case BillingStatementUpdated = 'billing_statement.updated';
    case BillingStatementDeleted = 'billing_statement.deleted';
    case BillingStatementFinalized = 'billing_statement.finalized';
    case BillingStatementSent = 'billing_statement.sent';
    case BillingStatementMarkedUncollectible = 'billing_statement.marked_uncollectible';
    case BillingStatementVoided = 'billing_statement.voided';
    case BillingStatementPaid = 'billing_statement.paid';
    case BillingStatementWillBeDue = 'billing_statement.will_be_due';
    case BillingStatementOverdue = 'billing_statement.overdue';
    case BillingStatementLineItemCreated = 'billing_statement_line_item.created';
    case BillingStatementLineItemUpdated = 'billing_statement_line_item.updated';
    case BillingStatementLineItemDeleted = 'billing_statement_line_item.deleted';
}
