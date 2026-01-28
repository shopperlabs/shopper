---
name: developing-shopper
description: Provides coding standards and patterns for Laravel Shopper development. Use when creating or modifying Models, Actions, Enums, Livewire components, migrations, or tests in any Shopper package.
---

# Developing Shopper

## Monorepo Structure

| Package             | Namespace           | Purpose                            |
|---------------------|---------------------|------------------------------------|
| `packages/admin`    | `Shopper\`          | Livewire components, views, routes |
| `packages/core`     | `Shopper\Core\`     | Models, Actions, Enums, Contracts  |
| `packages/sidebar`  | `Shopper\Sidebar\`  | Sidebar navigation                 |
| `packages/shipping` | `Shopper\Shipping\` | Shipping providers                 |

## Required in Every PHP File

```php
<?php

declare(strict_types=1);
```

## Class Element Order

1. Traits → 2. Cases → 3. Constants → 4. Properties → 5. Constructor → 6. Magic methods → 7. Methods (public → protected → private)

## Models

See [MODELS.md](MODELS.md) for the swappable model pattern.

```php
class Product extends Model implements ProductContract
{
    use HasFactory;
    use HasModelContract;
    use HasSlug;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'product';
    }

    public function getTable(): string
    {
        return shopper_table('products');
    }

    protected function casts(): array
    {
        return ['featured' => 'boolean'];
    }
}
```

## Actions

```php
final class CreateOrderAction
{
    public function execute(array $data): Order
    {
        // Single responsibility
    }
}
```

## Enums

```php
enum OrderStatus: string implements HasColor, HasIcon, HasLabel
{
    use ArrayableEnum;
    use HasEnumStaticMethods;

    case Pending = 'pending';

    public function getLabel(): string
    {
        return __('shopper-core::enum/order.pending');
    }
}
```

## Livewire Components

```php
class ProductForm extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    #[Computed]
    public function categories(): Collection
    {
        return resolve(CategoryContract::class)::query()->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.product-form');
    }
}
```

## Migrations

```php
return new class extends \Shopper\Core\Helpers\Migration
{
    public function up(): void
    {
        Schema::create($this->getTableName('products'), function (Blueprint $table): void {
            $this->addCommonFields($table, hasSoftDelete: true);
            $this->addSeoFields($table);
            $this->addShippingFields($table);
            $this->addForeignKey($table, 'brand_id', $this->getTableName('brands'));
        });
    }
};
```

## Testing

```php
beforeEach(function (): void {
    $this->user = User::factory()->create();
});

it('creates a product', function (): void {
    $this->actingAs($this->user);

    Livewire::test(ProductForm::class)
        ->set('data.name', 'Test')
        ->call('save')
        ->assertHasNoErrors();
})->group('products');
```

## Commands

```bash
composer test:sqlite   # Run tests
composer test:types    # PHPStan
composer cs            # Rector + Pint + Prettier
```
