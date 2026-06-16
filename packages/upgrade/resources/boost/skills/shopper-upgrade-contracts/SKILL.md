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
```

Add the missing methods, then verify the app boots and the payment drivers resolve.
