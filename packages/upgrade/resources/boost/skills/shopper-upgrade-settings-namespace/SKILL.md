---
name: shopper-upgrade-settings-namespace
description: Guides migration of the Settings subsystem from Shopper\Settings\* to Shopper\Navigation\Setting\* and the settings config changes. Needed when upgrading to Shopper 3.x if the app has custom settings pages, a published config/shopper/settings.php, or a published config/shopper/components/setting.php.
license: MIT
metadata:
    author: shopperlabs
---

# Shopper Upgrade: Settings Namespace Migration

You are upgrading a project from Shopper 2.x to 3.x. The settings subsystem moved under the navigation subsystem and several settings components were merged or removed.

## Pre-check

```bash
grep -rEl "Shopper\\\\Settings\\\\|setting-index|settings\.legal\.|settings\.team\.users" app/ config/ --include="*.php" 2>/dev/null
```

If **no results**, this upgrade does not apply. Skip it and inform the user.

## What changed

`Shopper\Settings\*` moved to `Shopper\Navigation\Setting\*`:

| 2.x                               | 3.x                                         |
|-----------------------------------|---------------------------------------------|
| `Shopper\Settings\Setting`        | `Shopper\Navigation\Setting\Setting`        |
| `Shopper\Settings\SettingManager` | `Shopper\Navigation\Setting\SettingManager` |
| `Shopper\Settings\Items\*Setting` | `Shopper\Navigation\Setting\Items\*Setting` |

Most code references (imports, type-hints, `extends` of the abstract `Setting` base) are rewritten automatically by `php artisan shopper:upgrade:rector`. Run that first. This skill covers what Rector cannot do.

## Step 1: Run Rector

```bash
php artisan shopper:upgrade:rector --dry-run
php artisan shopper:upgrade:rector
```

## Step 2: Custom setting items that extended a concrete item

The concrete `Shopper\Navigation\Setting\Items\*Setting` classes are now `final` and cannot be extended. If a custom class did `extends GeneralSetting` (or any other item), Rector leaves it untouched on purpose.

Re-point it at the abstract base instead:

```php
// Before
use Shopper\Settings\Setting;

class StoreLocaleSetting extends Setting { /* ... */ }
```

The abstract base `Shopper\Navigation\Setting\Setting` is the supported extension point. Register custom items through `config/shopper/settings.php` (Step 3).

## Step 3: Published config/shopper/settings.php

A copy published under 2.x still imports the old namespace and will fatal on boot. Update the `use` and register the new `AppearanceSetting`:

```php
// Before
use Shopper\Settings\Items;

// After
use Shopper\Navigation\Setting\Items;

return [
    'items' => [
        Items\GeneralSetting::class => true,
        Items\AppearanceSetting::class => true, // new in 3.x
        // ...
    ],
];
```

## Step 4: Published config/shopper/components/setting.php

These component keys were removed in 3.x. If your published copy still lists them, delete the lines:

| Removed key                                                | Replacement                                                         |
|------------------------------------------------------------|---------------------------------------------------------------------|
| `setting-index`                                            | none — the settings hub is gone, routes go straight to `general`    |
| `settings.legal.privacy` / `refund` / `shipping` / `terms` | merged into `Shopper\Livewire\Components\Settings\Legal\PolicyForm` |
| `settings.team.users` (`UsersRole`)                        | `settings.team.administrators` (`AdministratorsList`)               |

New keys added in 3.x: `appearance` and `settings.team.administrators`. The simplest path is to re-publish the component config and re-apply any customisations:

```bash
php artisan shopper:component:publish
```

## Search patterns

```bash
grep -rn "Shopper\\\\Settings\\\\\|setting-index\|settings\.legal\.\|settings\.team\.users\|UsersRole" app/ config/ --include="*.php"
```
