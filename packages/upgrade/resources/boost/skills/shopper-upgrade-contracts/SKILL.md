---
name: shopper-upgrade-contracts
description: Guides migration of model contracts moved across packages (ShopperUser, Cart, CartLine) and the new required methods on the PaymentDriver and Order contracts. Needed when upgrading to Shopper 3.x if the app has custom payment drivers, a custom Order or Cart model, or type-hints on the moved contracts.
license: MIT
metadata:
    author: shopperlabs
---

# Shopper Upgrade: Contract Changes

You are upgrading a project from Shopper 2.x to 3.x. Some model contracts moved to other packages, and two contracts gained methods that custom implementations must add.

## Pre-check

```bash
grep -rEl "Shopper\\\\Core\\\\Models\\\\Contracts\\\\(ShopperUser|Cart|CartLine)|implements PaymentDriver|PaymentDriver\b" app/ --include="*.php" 2>/dev/null
```

If **no results**, this upgrade does not apply. Skip it and inform the user.

## Contracts moved across packages

`php artisan shopper:upgrade:rector` rewrites these references automatically (imports, type-hints, `implements`). Run it first.

| 2.x                                         | 3.x                                      |
|---------------------------------------------|------------------------------------------|
| `Shopper\Core\Models\Contracts\ShopperUser` | `Shopper\Models\Contracts\ShopperUser`   |
| `Shopper\Core\Models\Contracts\Cart`        | `Shopper\Cart\Models\Contracts\Cart`     |
| `Shopper\Core\Models\Contracts\CartLine`    | `Shopper\Cart\Models\Contracts\CartLine` |

```bash
php artisan shopper:upgrade:rector
```

## New required method: PaymentDriver::mode()

Every class implementing `Shopper\Payment\Contracts\PaymentDriver` must now declare `mode()`. Without it the class is no longer a valid implementation and PHP throws a fatal error.

```php
use Shopper\Payment\Enum\PaymentMode;

/**
 * Return the live/test mode of the driver, or null when the driver has no
 * such notion (manual providers, unconfigured drivers).
 */
public function mode(): ?PaymentMode
{
    // Return PaymentMode::Live or PaymentMode::Test based on your driver's
    // configuration, or null when it does not apply.
    return $this->isLiveMode() ? PaymentMode::Live : PaymentMode::Test;
}
```

`PaymentMode` has two cases: `PaymentMode::Live` and `PaymentMode::Test`.

## Refunds that settle later: PaymentDriver::refundPayment()

`PaymentProcessingService::refund()` treats a `refundPayment()` result with `success: true`, `status: 'pending'` and a reference as a refund the provider has not settled yet. It journalizes a pending transaction, leaves the order's payment status alone, holds the amount against the refundable balance, and waits for a webhook carrying the same refund id.

A custom driver that already returns that pair must put the provider's id for the refund in `reference`, never the payment reference. With the payment reference, the second partial refund of an order is dropped from the journal and the first one never settles.

```php
// refundPayment()
return PaymentResult::pendingRefund($providerRefundId, $amount);

// handleWebhook(), once the provider reports the outcome
return new WebhookResult(
    action: WebhookAction::Refunded, // or WebhookAction::RefundFailed
    reference: $paymentReference,
    amount: $amount,
    data: ['refund_id' => $providerRefundId, 'failure_message' => $reason],
    eventId: $providerEventId,
);
```

A driver whose refunds settle at once keeps returning `success: true` with any status other than `pending`.

A `WebhookAction::RefundFailed` is final, including on a refund the provider had confirmed: the transaction becomes failed, `PaymentFailed` is dispatched, and the order's payment status steps back from `refunded` or `partially_refunded` to what the remaining refunds give. Code that treats `refunded` as a terminal payment status must allow for that.

`WebhookAction::RefundFailed` is a new case: a `match` on `WebhookAction` without a `default` arm must handle it.

### Callers of PaymentProcessingService::refund()

`refund()` takes a fifth, optional argument: the idempotency key of the refund request.

```php
$result = $payments->refund($order, $reference, $amount, $reason, idempotencyKey: $requestId);

if ($result->isPending()) {
    // accepted by the provider, not settled: the payment status has not changed yet
}
```

Generate the key once per refund the merchant asks for (a UUID stored with the form or the job) and send it again on every retry. A call that repeats a key already journalized for the order returns the state of that refund without calling the provider, so a double submit or a retried job refunds once. A refund that failed keeps its key: issue a new key to try again.

A key names one refund of one amount: repeating it with another amount throws a `PaymentException`. Do not derive it from the order alone (`"refund-{$order->id}"`), or the second partial refund of the order is refused.

Call `refund()` outside any open database transaction: it locks the order and reads the refunds on file, and on MySQL a transaction opened earlier would read them from a stale snapshot.

Without a key every call is a new refund. The key derived from the order, the reference, the amount and the number of refunds on file is gone: code that relied on two identical calls collapsing into one refund must now pass its own key.

Code that reads `$result->success` as "the customer has the money" must check `$result->isPending()` first.

The key is stored in the new `idempotency_key` column of `payment_transactions`, unique per order. Run the migrations.

A custom driver receives the key in `$context['idempotency_key']` as before. A driver whose provider can attach data to a refund should return the key in `data['idempotency_key']` of its `Refunded` and `RefundFailed` webhook results: a refund journalized by its webhook after the answer to `refundPayment()` was lost then carries the key, and the retry finds it.

## New required method: Order::promotions()

If the app uses a **custom Order model** that implements `Shopper\Core\Models\Contracts\Order` from scratch (not one that extends `Shopper\Core\Models\Order`), it must add the `promotions()` relationship:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Models\OrderPromotion;

public function promotions(): HasMany
{
    return $this->hasMany(OrderPromotion::class, 'order_id');
}
```

Most projects extend the default `Shopper\Core\Models\Order` and inherit this automatically — no change needed in that case.

## Search patterns

```bash
grep -rn "implements PaymentDriver\|implements Order\b\|Shopper\\\\Core\\\\Models\\\\Contracts" app/ --include="*.php"
grep -rn -A15 "function refundPayment" app/ --include="*.php" | grep "'pending'"
grep -rn "match (.*WebhookAction\|WebhookAction::" app/ --include="*.php"
```

Add the missing methods, then verify the app boots and the payment drivers resolve.
