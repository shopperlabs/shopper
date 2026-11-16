# Swappable Model Pattern

Shopper models can be replaced via `config/shopper/models.php`.

## Resolving Models

```php
use Shopper\Core\Models\Contracts\Product as ProductContract;

// Via contract (recommended)
resolve(ProductContract::class)::query()->get();

// Via class (static calls are proxied)
Product::query()->get();
Product::find(1);
```

## Creating a Model

### 1. Contract

`packages/core/src/Models/Contracts/Warehouse.php`:

```php
interface Warehouse
{
    public function isDefault(): bool;
    public function inventories(): HasMany;
}
```

### 2. Model

`packages/core/src/Models/Warehouse.php`:

```php
/**
 * @property-read int $id
 * @property-read string $name
 * @property-read bool $is_default
 */
class Warehouse extends Model implements WarehouseContract
{
    use HasFactory;
    use HasModelContract;

    protected $guarded = [];

    public static function configuredClass(): string
    {
        return config('shopper.models.warehouse', static::class);
    }

    public function getTable(): string
    {
        return shopper_table('warehouses');
    }

    public function isDefault(): bool
    {
        return $this->is_default;
    }

    /**
     * @return HasMany<Inventory, $this>
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(config('shopper.models.inventory'), 'warehouse_id');
    }

    protected function casts(): array
    {
        return ['is_default' => 'boolean'];
    }
}
```

### 3. Register

In `packages/core/config/models.php`:

```php
'warehouse' => Models\Warehouse::class,
```

## Available Traits

| Trait                  | Purpose                              |
|------------------------|--------------------------------------|
| `HasModelContract`     | Enables swapping                     |
| `HasSlug`              | Unique slugs, `findBySlug()`         |
| `HasPrices`            | `prices()`, `getPrice()`             |
| `HasStock`             | Inventory tracking                   |
| `HasDimensions`        | weight, height, width, depth, volume |
| `HasMediaCollections`  | Spatie Media Library                 |
| `HasDiscounts`         | `discounts()`                        |
| `HasZones`             | `zones()` polymorphic                |
| `InteractsWithReviews` | `reviews()`                          |

## Relationships

Always use `config()` for related models:

```php
public function brand(): BelongsTo
{
    return $this->belongsTo(config('shopper.models.brand'), 'brand_id');
}
```

## Extending Models

```php
namespace App\Models;

use Shopper\Core\Models\Product as ShopperProduct;

class Product extends ShopperProduct
{
    public function isPublished(): bool
    {
        return parent::isPublished() && $this->custom_condition;
    }
}
```

Then in `config/shopper/models.php`:

```php
'product' => App\Models\Product::class,
```
