# Shopper Stripe

The Stripe payment driver for [Shopper](https://shopperphp.com). It authorizes and captures
payments through Payment Intents, refunds them, and settles the provider events Stripe pushes
to the store webhook endpoint.

## Requirements

- PHP 8.3+
- Laravel 12.x or 13.x
- [`shopper/framework`](https://github.com/shopperlabs/shopper) installed and migrated

## Installation

```bash
composer require shopper/stripe
```

The service provider is auto-discovered and registers the `stripe` driver on the payment
manager. Enable it and add your keys to `.env`:

```dotenv
PAYMENT_STRIPE_ENABLED=true
STRIPE_SECRET_KEY=sk_test_...
STRIPE_PUBLISHABLE_KEY=pk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
STRIPE_CAPTURE_METHOD=manual
```

A driver left disabled stays out of the payment methods the admin offers, even once its
credentials are filled in. Test mode is read from the secret key prefix.

`STRIPE_CAPTURE_METHOD` accepts `manual` (authorize at checkout, capture from the admin when the
order ships) or `automatic` (capture on confirmation).

## Webhooks

Point a Stripe webhook endpoint at `/store/webhooks/stripe` and subscribe it to:

- `payment_intent.amount_capturable_updated`
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `payment_intent.canceled`
- `refund.created`
- `refund.updated`
- `refund.failed`

Every event is verified against `STRIPE_WEBHOOK_SECRET` before it is applied. The driver refuses
to run without it.

A refund Stripe has not settled yet is journalized as pending. The order's payment status changes when
`refund.updated` confirms it, and `refund.failed` marks it failed and gives its amount back to the
refundable balance. Without these three refund events the pending refund of an order settles only
when `php artisan shopper:payments:reconcile --pull` asks Stripe for its state, once it has been
pending for the `--minutes` delay (15 by default). The automatic refund of a payment left without an
order has no transaction to check: its failure is only known from `refund.failed`.

The driver sends the idempotency key of a refund to Stripe as the request key and stores it in the
refund's metadata under `idempotency_key`, so the refund events carry it back to the store.
Stripe forgets a request key after 24 hours: past that delay, the retry of a refund whose answer was
lost and whose events have not reached the store yet creates a second refund. The store journalizes
both and reports the duplicate.

## Configuration

```bash
php artisan vendor:publish --tag=shopper-stripe-config
```

This publishes `config/shopper/stripe.php`.

## Documentation

Read the [payment documentation](https://docs.shopperphp.com) for the checkout flow, the
order payment states and the reconciliation command.
