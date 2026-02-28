# CLAUDE.md

This file provides guidance to Claude Code when working with code in the Shopper repository.

## Project Overview

Shopper is a headless e-commerce admin panel built for Laravel using the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire). It is organized as a monorepo with eight packages.

## Monorepo Structure

```
packages/
├── admin/      → Main admin UI (Livewire components, Filament forms/tables, views, routes, assets)
├── cart/       → Cart management with pipeline-based calculation, discounts, and taxes
├── core/       → E-commerce domain logic (Models, Actions, Enums, Contracts, Stock, Taxes)
├── payment/    → Payment processing with extensible driver architecture
├── shipping/   → Shipping providers integration with driver architecture
├── sidebar/    → Sidebar navigation builder (DDD-style architecture)
├── stripe/     → Stripe payment driver for the payment system
└── types/      → TypeScript type definitions (NPM package, no PHP)
```

Each PHP package has its own service provider:
- `Shopper\ShopperServiceProvider` (admin)
- `Shopper\Cart\CartServiceProvider` (cart)
- `Shopper\Core\CoreServiceProvider` (core)
- `Shopper\Payment\PaymentServiceProvider` (payment)
- `Shopper\Shipping\ShippingServiceProvider` (shipping)
- `Shopper\Sidebar\SidebarServiceProvider` (sidebar)
- `Shopper\Stripe\StripeServiceProvider` (stripe)

Packages are split to separate repositories via the `monorepo-split.yml` workflow on push to `*.x` branches.

## Development Commands

### Testing

```bash
composer test                # Full suite (type-coverage + types + sqlite)
composer test:unit           # Pest with coverage (80% minimum) and parallel
composer test:sqlite         # Tests against SQLite
composer test:mysql          # Tests against MySQL
composer test:pgsql          # Tests against PostgreSQL
composer test:all-databases  # Run all database tests
composer test:types          # PHPStan level 5
composer test:type-coverage  # Type coverage (90% minimum)
composer test:refactor       # Rector dry-run
composer test:lint           # Laravel Pint check
```

### Code Style

```bash
composer lint       # Run Laravel Pint (fix)
composer refactor   # Run Rector (fix)
composer cs         # Run refactor + lint + prettier
```

### Assets

```bash
npm run dev       # Watch mode (CSS + JS in parallel)
npm run build     # Production build (CSS + JS)
npm run prettier  # Format Blade/CSS/JS files
```

### Build Tools

- **Tailwind CSS v4** with PostCSS for stylesheets
- **esbuild** for JavaScript bundling (configured in `scripts/build.js`)
- **Prettier** with Blade and Tailwind plugins (print width: 120, single quotes, no semicolons)

## Coding Standards

### Strict Types

Every PHP file MUST declare strict types:

```php
<?php

declare(strict_types=1);
```

### Class Element Order

Enforced by Pint (`ordered_class_elements`):

1. Traits (`use` statements)
2. Cases (for enums)
3. Constants
4. Properties
5. Constructor
6. Magic methods
7. Methods (public, then protected, then private)

### Naming Conventions

- Use descriptive variable names. Avoid abbreviations like `$e`, `$c`, `$prod`.
- Use backticks when referencing code in test names: `` it('can resolve `Product` from the container') ``
- Method references include parentheses: `` `getPrice()` method ``

### Code Style Rules

- **Arrow functions** preferred over closures when possible
- **Strict comparison** (`===`, `!==`) always
- **Void return types** required on methods that return nothing
- **No parentheses** on instantiation without arguments: `new Foo` not `new Foo()`
- **Multiline arguments** when a method has multiple parameters
- **MB string functions** preferred over standard string functions
- No superfluous `else`/`elseif` after early returns

### PHPDoc

Only add PHPDoc when it provides type information beyond what PHP can express natively:

```php
// Good: Adds generic type info not expressible in PHP
/** @return BelongsTo<Brand, $this> */
public function brand(): BelongsTo

// Bad: Redundant with native type
/** @return string */
public function name(): string
```

Use `@property-read` annotations on model classes for IDE support.

## Architecture Patterns

### Models

- Use `protected $guarded = []` (not `$fillable`)
- Use `casts()` method returning an array (not `$casts` property)
- Use `Attribute::get()` / `Attribute::set()` for accessors/mutators
- Table names resolved via `shopper_table('table_name')` helper
- Implement contracts from `Shopper\Core\Contracts`
- Compose focused traits: `HasMedia`, `HasSlug`, `HasPrices`, `HasDimensions`, etc.

```php
class Product extends Model implements ProductContract, Priceable, SpatieHasMedia
{
    use HasFactory;
    use HasMedia;
    use HasPrices;
    use HasSlug;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('products');
    }

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'type' => ProductType::class,
        ];
    }
}
```

### Model Contract Pattern

Models are swappable via configuration. Use `HasModelContract` trait and resolve models through:

```php
resolve(ProductContract::class)  // Returns the configured model class
config('shopper.models.product') // Configuration key for the model
```

### Action Classes

- Marked as `final`
- Single responsibility with one public method (typically `execute()`)
- Injected dependencies via constructor

```php
final class SyncCollectionProductsAction
{
    public function execute(Collection $collection): int
    {
        // ...
    }
}
```

### Livewire Components

- Typed public properties
- `#[Computed]` attribute for lazy-loaded properties
- Return type `View` on `render()` methods
- Resolve dependencies via container: `resolve(ContractClass::class)`

### Enums

- Backed enums (`: string` or `: int`)
- Implement Filament interfaces: `HasLabel`, `HasColor`, `HasIcon`, `HasDescription`
- Use `ArrayableEnum` and `HasEnumStaticMethods` traits
- Labels use translation keys: `__('shopper-core::enum/product.virtual')`

```php
enum ProductType: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case External = 'external';
    case Standard = 'standard';
    case Virtual = 'virtual';
    case Variant = 'variant';

    public function getLabel(): string
    {
        return match ($this) {
            self::External => __('shopper-core::enum/product.external'),
            // ...
        };
    }
}
```

### Contracts/Interfaces

- Use generics in PHPDoc: `@template TModel of Model`
- Specify return types with full generic information
- Placed in `packages/core/src/Contracts/` or `packages/core/src/Models/Contracts/`

## Testing

- **Framework**: Pest PHP with Livewire and Laravel plugins
- Use `it()` syntax, not `test()`
- Use `beforeEach()` for shared setup
- Use arrow functions for callbacks
- Use Mockery for mocking
- Use `expect()` for assertions

```php
beforeEach(function (): void {
    $this->user = User::factory()->create();
});

it('can display the product list', function (): void {
    $this->actingAs($this->user);

    Livewire::test(ProductList::class)
        ->assertSuccessful();
});
```

## Static Analysis

- **PHPStan**: Level 5 with Larastan extension
- **Rector**: Type coverage level 8 (maximum)
- **Test coverage**: Minimum 80%
- **Type coverage**: Minimum 90%

## Dependencies

### Key PHP Packages

- `filament/filament` ^4.7 (headless admin panel components)
- `livewire/livewire` ^3.7
- `spatie/laravel-permission` ^6.24
- `spatie/laravel-media-library` ^11.5
- `stripe/stripe-php` ^16.0 (stripe package)
- `ivanmitrikeski/laravel-shipping` ^1.0 (shipping package)

### PHP Version

- Minimum: PHP 8.3
- Tested on: PHP 8.3 and 8.4

### Laravel Version

- Supports Laravel 11.x and 12.x

## File Organization

- Views: `packages/admin/resources/views/`
- CSS: `packages/admin/resources/css/`
- JS: `packages/admin/resources/js/` and `packages/sidebar/resources/js/`
- Translations: `packages/admin/resources/lang/` and `packages/core/resources/lang/`
- Config: `packages/*/config/`
- Migrations: `packages/core/database/migrations/`
- Routes: `packages/admin/routes/`
- Render hooks: `packages/admin/src/View/*RenderHook.php` (scoped by business domain)
- Payment drivers: `packages/payment/src/Drivers/`
- Shipping drivers: `packages/shipping/src/Drivers/`
- Cart pipelines: `packages/cart/src/Pipelines/`
- TypeScript types: `packages/types/`
