---
name: shopper-upgrade-product-edit
description: Guides migration of the product edit screen from Livewire form components to pages, and the new section registration in config/shopper/components/product.php. Needed when upgrading to Shopper 3.x if the app overrode product edit components or referenced their Livewire component names in custom views.
license: MIT
metadata:
    author: shopperlabs
---

# Shopper Upgrade: Product Edit Restructure

You are upgrading a project from Shopper 2.x to 3.x. The product edit screen was reworked from a set of Livewire form components into an extensible navigation shell made of pages and registered sections.

## Pre-check

```bash
grep -rEl "Products\\\\Form\\\\|products\.form\.|product-edit" app/ config/ resources/ --include="*.php" --include="*.blade.php" 2>/dev/null
```

If **no results**, this upgrade does not apply. Skip it and inform the user.

## Classes moved

`php artisan shopper:upgrade:rector` rewrites these automatically. Run it first.

| 2.x                                                         | 3.x                                         |
|-------------------------------------------------------------|---------------------------------------------|
| `Shopper\Livewire\Components\Products\Form\Edit`            | `Shopper\Livewire\Pages\Product\Overview`   |
| `Shopper\Livewire\Components\Products\Form\Attributes`      | `Shopper\Livewire\Pages\Product\Attributes` |
| `Shopper\Livewire\Components\Products\Form\Files`           | `Shopper\Livewire\Pages\Product\Files`      |
| `Shopper\Livewire\Components\Products\Form\Inventory`       | `Shopper\Livewire\Pages\Product\Inventory`  |
| `Shopper\Livewire\Components\Products\Form\Media`           | `Shopper\Livewire\Pages\Product\Media`      |
| `Shopper\Livewire\Components\Products\Form\RelatedProducts` | `Shopper\Livewire\Pages\Product\Related`    |
| `Shopper\Livewire\Components\Products\Form\Seo`             | `Shopper\Livewire\Pages\Product\Seo`        |
| `Shopper\Livewire\Components\Products\Form\Shipping`        | `Shopper\Livewire\Pages\Product\Shipping`   |
| `Shopper\Livewire\Components\Products\Form\Variants`        | `Shopper\Livewire\Pages\Product\Variants`   |

```bash
php artisan shopper:upgrade:rector
```

## Published config/shopper/components/product.php

The `product-edit` page key and the nine `products.form.*` component keys were removed. The new config registers one page per tab plus a `sections` list that drives the edit sidebar:

```php
return [
    'sections' => [
        Sections\OverviewSection::class => true,
        Sections\MediaSection::class => true,
        // ... AttributesSection, VariantsSection, InventorySection,
        //     PricingSection, ShippingSection, FilesSection, SeoSection,
        //     RelatedProductsSection
    ],

    'pages' => [
        'product-overview' => Livewire\Pages\Product\Overview::class,
        'product-media' => Livewire\Pages\Product\Media::class,
        // ... product-attributes, product-variants, product-inventory,
        //     product-pricing, product-shipping, product-files,
        //     product-seo, product-related
    ],

    'components' => [
        'products.delete-action' => Components\Products\DeleteAction::class,
    ],
];
```

If you published this config under 2.x, re-publish it and re-apply your overrides:

```bash
php artisan shopper:component:publish
```

To add a custom tab, register a section class in `sections` and a page in `pages` rather than overriding a monolithic edit component.

## Custom Blade views

Livewire components are registered as `shopper-{key}`, so the old `products.form.*` keys resolved to `shopper-products.form.*` and the new page keys resolve to `shopper-product-*`. Update any custom view that rendered a product form component by name:

```blade
{{-- Before --}}
@livewire('shopper-products.form.edit', ['product' => $product])

{{-- After --}}
@livewire('shopper-product-overview', ['product' => $product])
```

Check the exact aliases in `config/shopper/components/product.php` if a reference does not resolve.

## Search patterns

```bash
grep -rn "Products\\\\Form\\\\\|products\.form\.\|product-edit" app/ config/ resources/
```
