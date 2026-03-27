---
name: shopper-upgrade-permissions
description: Guides migration of permission names from underscore format (read_orders) to dot notation (orders.read). Required when upgrading to Shopper 3.x.
---

# Shopper Upgrade: Permission Names Migration

You are upgrading a project from Shopper 2.x to 3.x. This prompt covers the migration of permission names from underscore format to dot notation.

## Pre-check

First, check if this upgrade is needed:

```bash
grep -rE "authorize\('[a-z]+_[a-z]|->can\('[a-z]+_[a-z]|permission\('[a-z]+_[a-z]" app/ --include="*.php" -l 2>/dev/null
```

If **no results** are found, this upgrade does not apply. Skip this prompt entirely and inform the user.

## What Changed

Shopper 3.x uses dot notation for all permissions instead of underscore format. This aligns with the `resource.action` convention used by Spatie Permission and most Laravel packages.

## Permission Mapping

| 2.x (underscore) | 3.x (dot notation) |
|---|---|
| `access_setting` | `system.settings` |
| `view_users` | `system.users` |
| `add_attributes` | `attributes.create` |
| `browse_attributes` | `attributes.browse` |
| `delete_attributes` | `attributes.delete` |
| `edit_attributes` | `attributes.edit` |
| `add_brands` | `brands.create` |
| `browse_brands` | `brands.browse` |
| `delete_brands` | `brands.delete` |
| `edit_brands` | `brands.edit` |
| `add_categories` | `categories.create` |
| `browse_categories` | `categories.browse` |
| `delete_categories` | `categories.delete` |
| `edit_categories` | `categories.edit` |
| `add_collections` | `collections.create` |
| `browse_collections` | `collections.browse` |
| `delete_collections` | `collections.delete` |
| `edit_collections` | `collections.edit` |
| `add_customers` | `customers.create` |
| `browse_customers` | `customers.browse` |
| `delete_customers` | `customers.delete` |
| `read_customers` | `customers.read` |
| `add_discounts` | `discounts.create` |
| `browse_discounts` | `discounts.browse` |
| `delete_discounts` | `discounts.delete` |
| `edit_discounts` | `discounts.edit` |
| `add_inventories` | `inventories.create` |
| `browse_inventories` | `inventories.browse` |
| `delete_inventories` | `inventories.delete` |
| `edit_inventories` | `inventories.edit` |
| `browse_orders` | `orders.browse` |
| `edit_orders` | `orders.edit` |
| `read_orders` | `orders.read` |
| `add_products` | `products.create` |
| `browse_products` | `products.browse` |
| `delete_products` | `products.delete` |
| `edit_products` | `products.edit` |
| `edit_product_variants` | `products.variants.edit` |
| `browse_reviews` | `reviews.browse` |
| `delete_reviews` | `reviews.delete` |
| `add_suppliers` | `suppliers.create` |
| `browse_suppliers` | `suppliers.browse` |
| `delete_suppliers` | `suppliers.delete` |
| `edit_suppliers` | `suppliers.edit` |
| `browse_tags` | `tags.browse` |
| `delete_tags` | `tags.delete` |
| `edit_tags` | `tags.edit` |

Key changes:
- `add_` prefix → `.create` action (not `.add`)
- `access_setting` → `system.settings`
- `view_users` → `system.users`
- `edit_product_variants` → `products.variants.edit` (nested dot notation, no underscore)

## Migration Steps

### Step 1: Update authorize calls

**Before:**
```php
$this->authorize('read_orders');
$this->authorize('add_products');
$this->authorize('access_setting');
```

**After:**
```php
$this->authorize('orders.read');
$this->authorize('products.create');
$this->authorize('system.settings');
```

### Step 2: Update can/cannot checks

@verbatim
**Before:**
```php
@can('browse_products')
$user->can('edit_brands')
```

**After:**
```php
@can('products.browse')
$user->can('brands.edit')
```
@endverbatim

### Step 3: Update Gate definitions

**Before:**
```php
Gate::allows('delete_customers')
```

**After:**
```php
Gate::allows('customers.delete')
```

### Step 4: Update database

Run the Shopper upgrade command to rename permissions in the database:
```bash
php artisan shopper:upgrade:permissions
```

This command updates all permission names in the Spatie permissions table from underscore to dot notation.

### Step 5: Search and replace

```bash
grep -rn "authorize\|->can\|->cannot\|Gate::allows\|Gate::denies" app/ app/Shopper/ --include="*.php" | grep -E "'[a-z]+_[a-z]"
```

Apply the mapping for each match. Verify the application works after each batch of changes.
