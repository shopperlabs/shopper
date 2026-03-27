# Shopper Upgrade: Money Storage Migration

You are upgrading a project that uses Laravel Shopper to version 2.7+. The most critical change is how monetary values are stored and read.

## What Changed

Shopper now follows the Stripe/Shopify standard: **all monetary values are stored in the smallest currency unit** (cents for USD/EUR/GBP, raw value for zero-decimal currencies like XAF/JPY).

Previously, Eloquent accessors on models automatically converted between human values and cents:
- Setter: `$order->price_amount = 12.00` → stored `1200` in DB
- Getter: DB `1200` → returned `12.00`

Now there are **no accessors**. The DB value IS the smallest unit, and `shopper_money_format()` handles display conversion.

## Step-by-Step Migration

### Step 1: Assessment

Search the codebase for these patterns that need updating:

**High priority — will break immediately:**
- Direct reads of price properties for display: `$order->price_amount`, `$item->unit_price_amount`, `$price->amount`, `$option->price`
- Direct writes of price values: `Order::create(['price_amount' => 12.00])`, `Price::create(['amount' => 163])`
- Cart pipeline results: `$context->subtotal`, `$context->total`, `$context->discountTotal`, `$context->taxTotal`
- Payment gateway amounts: any code that multiplies by 100 before sending to Stripe/payment providers

**Medium priority — display issues:**
- Custom Blade views using `Number::currency()` directly instead of `shopper_money_format()`
- Filament table columns using `->money()` instead of `->currency()` macro
- Custom admin components displaying prices

**Low priority — seeders and tests:**
- Factory values and seeders passing human values instead of cents

### Step 2: Create a safety branch

```bash
git checkout -b upgrade/shopper-money-storage
```

Run existing tests to establish a baseline before making changes.

### Step 3: Fix price reads (display)

**Before:**
```php
// Displaying a price directly
echo $order->price_amount; // Was 12.00, now returns 1200
```

**After:**
```php
// Use shopper_money_format() for display
echo shopper_money_format($order->price_amount, $order->currency_code); // "£12.00"
```

Search for all occurrences:
```
$order->price_amount
$order->tax_amount
$item->unit_price_amount
$item->tax_amount
$item->discount_amount
$item->total
$price->amount
$price->compare_amount
$price->cost_amount
$option->price
$discount->value (when type is FixedAmount)
$line->unit_price_amount
```

Any of these used for **display** must go through `shopper_money_format($value, $currencyCode)`.

Any of these used for **calculation** should work as-is — calculations in cents are correct.

### Step 4: Fix price writes

**Before:**
```php
Order::create(['price_amount' => 12.00]); // Setter did *100 → 1200
Price::create(['amount' => 163]); // Setter did *100 → 16300
```

**After:**
```php
Order::create(['price_amount' => 1200]); // Stored directly
Price::create(['amount' => 16300]); // Stored directly
```

For every `create()`, `update()`, or direct assignment on monetary fields, the value must now be in the smallest unit.

### Step 5: Fix payment gateway integration

**Before:**
```php
// Stripe expects cents, so storefront code multiplied
$amount = $order->price_amount * 100; // 12.00 * 100 = 1200
Stripe::charge($amount);
```

**After:**
```php
// Value is already in cents, pass directly
$amount = $order->price_amount; // Already 1200
Stripe::charge($amount);
```

Search for patterns like `* 100` near Stripe/payment calls and remove the multiplication.

### Step 6: Fix cart pipeline consumers

**Before:**
```php
$context = $cartManager->calculate($cart);
$subtotal = $context->subtotal; // Was 25.00 (human value)
```

**After:**
```php
$context = $cartManager->calculate($cart);
$subtotal = $context->subtotal; // Now 2500 (cents)
// For display:
echo shopper_money_format($subtotal, $cart->currency_code);
```

### Step 7: Fix zero-decimal currency data

If the project uses zero-decimal currencies (XAF, JPY, KRW, etc.), existing data in the database has been incorrectly multiplied by 100 by the old setters.

Run the Shopper upgrade command:
```bash
php artisan shopper:fix-zero-decimal-currencies
```

This command corrects the stored values for all zero-decimal currency amounts.

### Step 8: Update seeders and factories

All seed data must use smallest currency unit values:
```php
// Before
'amount' => 163 // Meant $163.00, setter did *100

// After
'amount' => 16300 // $163.00 in cents

// XAF (zero-decimal) stays the same
'amount' => 98000 // 98000 XAF
```

### Step 9: Run tests

```bash
php artisan test
```

Fix any remaining assertion failures — they will typically be values that are now 100x different.

## Key Rules

1. **DB always stores smallest unit** — cents for USD/EUR/GBP, raw for XAF/JPY
2. **`shopper_money_format($amount, $currency)` for display** — handles the division
3. **No manual `/ 100` or `* 100`** — the helper and MoneyInput handle conversion
4. **`is_no_division_currency($code)` exists** — use it if you need custom logic for zero-decimal currencies
5. **Filament columns use `->currency()` macro** — not `->money()`
6. **MoneyInput component** — use `MoneyInput::make('field')->currency('USD')` in Filament forms for automatic hydrate/dehydrate
