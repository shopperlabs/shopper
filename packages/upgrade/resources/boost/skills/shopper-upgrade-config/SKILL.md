---
name: shopper-upgrade-config
description: Guides reconciliation of published config files (admin notifications, campaign feature, themes, cart pipelines, max_promotions, shipping rates cache, carrier driver packages, discount components) after upgrading to Shopper 3.x. Only needed for config files the app published under 2.x.
license: MIT
metadata:
    author: shopperlabs
---

# Shopper Upgrade: Published Config Reconciliation

You are upgrading a project from Shopper 2.x to 3.x. `shopper:install` publishes config files under `config/shopper/` via `vendor:publish --tag=shopper-config`. A file published under 2.x keeps its old shape and is loaded over the package defaults, so new 3.x keys are absent from your editable copy.

Every provider calls `mergeConfigFrom` before `publishes`, so a **missing scalar/array key is backfilled by the 3.x default at runtime** and most omissions are non-fatal. Reconcile anyway to keep your published copy in sync and editable. Two changes do affect behaviour and are flagged below.

## Pre-check

List which default-published files your app actually has:

```bash
ls config/shopper/{admin,features,themes,cart,payment,shipping}.php config/shopper/components/discount.php 2>/dev/null
```

Reconcile only the files that exist. A file with no result inherits the 3.x default automatically — skip it.

> `config/shopper/settings.php` is covered by the `shopper-upgrade-settings-namespace` skill (it carries a `use` statement that fatals on boot if left stale). Handle it there.

## config/shopper/admin.php

The admin topbar bell (database notifications) added a top-level `notifications` block. Add it before the closing `];`:

```php
'notifications' => [
    'database' => [
        'enabled' => false,
        'polling_interval' => '30s',
    ],
],
```

`enabled => false` keeps current behaviour. Set it to `true` to persist domain events for administrators; set `polling_interval` to `null` to rely solely on broadcasting.

## config/shopper/features.php

The promotion engine added a `campaign` feature toggle. Add it to the features array:

```php
'campaign' => FeatureState::Enabled,
```

## config/shopper/themes.php

New file in 3.x — it does not exist in a 2.x project. The admin theme system falls back to the package default automatically, but to customise themes you need the published copy:

```bash
php artisan vendor:publish --tag=shopper-config
```

This only writes files you do not already have; your existing published configs are left untouched.

## config/shopper/cart.php

The promotion engine added a pipeline stage and a limit. A 2.x copy is missing both, so **automatic promotions never run** (the `pipelines.cart` list is replaced wholesale, not merged) and the stacking limit is undefined.

Add `ApplyAutomaticPromotions` to the cart pipeline, before `ApplyDiscounts`:

```php
'pipelines' => [
    'cart' => [
        Pipelines\CalculateLines::class,
        Pipelines\ApplyAutomaticPromotions::class, // add this
        Pipelines\ApplyDiscounts::class,
        Pipelines\CalculateTax::class,
        Pipelines\Calculate::class,
    ],
],
```

Add the stacking limit at the top level of the config:

```php
// The highest number of promotions that may apply to a single cart at once.
'max_promotions' => 5,
```

## config/shopper/payment.php

Same move on the payment side: `shopper/stripe` owns its config, so the `drivers` block here is dead and should be deleted. It duplicated the Stripe credentials that `config/shopper/stripe.php` already reads from the same env variables, and its `paypal` entry never had a driver behind it.

`PAYMENT_STRIPE_ENABLED` is unchanged, it is simply read from `shopper.stripe.enabled` now. Publish the Stripe copy to edit it:

```bash
php artisan vendor:publish --tag=shopper-stripe-config
```

## config/shopper/shipping.php

A shipping-rates cache TTL was added. Add it at the top level:

```php
'rates_cache_ttl' => env('SHIPPING_RATES_CACHE_TTL', 600),
```

### Delete the `drivers` block from this file

Each carrier now ships as its own opt-in package owning its own config file, the way `shopper/stripe` does. Nothing reads `shopper.shipping.drivers.*` any more, so the whole `drivers` block in your published copy is dead. Delete it, including the `canada_post` and `purolator` entries, which were config-only in 2.x with no driver behind them.

| Carrier | Package | Config file | Config key |
|---------|----------------------|----------------------------|-----------------|
| UPS     | `shopper/ups`        | `config/shopper/ups.php`   | `shopper.ups`   |
| FedEx   | `shopper/fedex`      | `config/shopper/fedex.php` | `shopper.fedex` |
| USPS    | `shopper/usps`       | `config/shopper/usps.php`  | `shopper.usps`  |

```bash
composer require shopper/ups
php artisan vendor:publish --tag=shopper-ups-config
```

**The env names did not change.** `SHIPPING_UPS_ENABLED`, `SHIPPING_SANDBOX`, `UPS_CLIENT_ID` and the rest read exactly as before, so a project that kept its credentials in `.env`, which is the normal case, has nothing to move.

Only a project that wrote literal values into the published `drivers` block has to carry them over. The keys are flat now, there is no `credentials` nesting:

```php
// config/shopper/shipping.php in 2.x
'drivers' => ['ups' => ['enabled' => true, 'credentials' => ['client_id' => 'abc']]],

// config/shopper/ups.php in 3.x
'enabled' => true,
'client_id' => 'abc',
```

Without the package the carrier drops out of `Shipping::availableDrivers()`, disappears from the shipping admin, and `Shipping::driver('ups')` throws `Driver [ups] not supported.` The `shopper:upgrade` command prints the exact `composer require` line for the carriers you had enabled, reading either your 2.x config or your `.env`.

## config/shopper/components/discount.php

The discount form slide-over was removed and replaced by the promotion components. If your published copy still registers the old key, replace it:

| Removed                     | Added                                   |
|-----------------------------|-----------------------------------------|
| `slide-overs.discount-form` | `slide-overs.add-promotion`             |
|                             | `slide-overs.discount-products-picker`  |
|                             | `slide-overs.discount-customers-picker` |
|                             | `discounts.stats-panel`                 |
|                             | `discount-edit` (page)                  |

The simplest path is to re-publish the component config and re-apply your overrides:

```bash
php artisan shopper:component:publish
```

## Verify

After merging, confirm: the admin bell appears when `notifications.database.enabled` is `true`, a cart with an automatic promotion applies it, and the discount admin screens open without a missing-component error.
