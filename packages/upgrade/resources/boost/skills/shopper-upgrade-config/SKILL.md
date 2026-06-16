---
name: shopper-upgrade-config
description: Guides reconciliation of published config files (cart pipelines, max_promotions, discount components) after upgrading to Shopper 3.x. Only needed if the app published config/shopper/cart.php or config/shopper/components/discount.php under 2.x.
license: MIT
metadata:
    author: shopperlabs
---

# Shopper Upgrade: Published Config Reconciliation

You are upgrading a project from Shopper 2.x to 3.x. A config file published under 2.x keeps its old shape and silently overrides the package defaults. This skill covers the two config files whose changes affect runtime behaviour.

## Pre-check

```bash
ls config/shopper/cart.php config/shopper/components/discount.php 2>/dev/null
```

If **no results**, the app never published these files and inherits the 3.x defaults automatically. Skip this skill.

## config/shopper/cart.php

The promotion engine added a pipeline stage and a limit. A 2.x copy is missing both, so automatic promotions never run and the stacking limit is undefined.

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

## config/shopper/components/discount.php

The discount form slide-over was removed and replaced by the promotion components. If your published copy still registers the old key, replace it:

| Removed | Added |
|---|---|
| `slide-overs.discount-form` | `slide-overs.add-promotion` |
| | `slide-overs.discount-products-picker` |
| | `slide-overs.discount-customers-picker` |
| | `discounts.stats-panel` |
| | `discount-edit` (page) |

The simplest path is to re-publish the component config and re-apply your overrides:

```bash
php artisan shopper:component:publish
```

## Verify

After merging, confirm a cart with an automatic promotion applies it, and the discount admin screens open without a missing-component error.
