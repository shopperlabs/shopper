<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Shopper\Core\Exceptions\LazyStockLoadingException;
use Shopper\Core\Models\InventoryHistory;

trait HasStock
{
    protected static bool $preventLazyStockLoading = false;

    public static function preventLazyStockLoading(bool $prevent = true): void
    {
        static::$preventLazyStockLoading = $prevent;
    }

    /**
     * Batch-load current stock for a collection of stockable models in a single query,
     * avoiding N+1 when iterating over products or variants.
     *
     * @param  Collection<int, Model>  $models
     */
    public static function loadCurrentStock(Collection $models): void
    {
        if ($models->isEmpty()) {
            return;
        }

        $first = $models->first();

        $stocks = InventoryHistory::query()
            ->selectRaw('stockable_id, SUM(quantity) as aggregate')
            ->where('stockable_type', $first->getMorphClass())
            ->whereIn('stockable_id', $models->pluck($first->getKeyName()))
            ->where('created_at', '<=', Carbon::now())
            ->groupBy('stockable_id')
            ->pluck('aggregate', 'stockable_id');

        $models->each(function (Model $model) use ($stocks): void {
            $model->setAttribute('real_stock', (int) ($stocks[$model->getKey()] ?? 0));
        });
    }

    /**
     * Batch-load stock for a specific inventory location.
     *
     * @param  Collection<int, Model>  $models
     */
    public static function loadStockForInventory(Collection $models, int $inventoryId): void
    {
        if ($models->isEmpty()) {
            return;
        }

        $first = $models->first();

        $stocks = InventoryHistory::query()
            ->selectRaw('stockable_id, SUM(quantity) as aggregate')
            ->where('stockable_type', $first->getMorphClass())
            ->whereIn('stockable_id', $models->pluck($first->getKeyName()))
            ->where('inventory_id', $inventoryId)
            ->where('created_at', '<=', Carbon::now())
            ->groupBy('stockable_id')
            ->pluck('aggregate', 'stockable_id');

        $models->each(function (Model $model) use ($stocks): void {
            $model->setAttribute('real_stock', (int) ($stocks[$model->getKey()] ?? 0));
        });
    }

    public function inStock(int $quantity = 1): bool
    {
        return $this->stock > 0 && $this->stock >= $quantity;
    }

    public function getStock(string|DateTimeInterface|null $date = null): int
    {
        $date = $date ?: Carbon::now();

        if (! $date instanceof DateTimeInterface) {
            $date = Carbon::create($date);
        }

        return (int) $this->inventoryHistories()
            ->where('created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->sum('quantity');
    }

    public function stockInventory(int $inventoryId, ?string $date = null): int
    {
        $date = $date ?: Carbon::now();

        if (! $date instanceof DateTimeInterface) {
            $date = Carbon::create($date);
        }

        return (int) $this->inventoryHistories()
            ->where('created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->where('inventory_id', $inventoryId)
            ->sum('quantity');
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function mutateStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory
    {
        return $this->createStockMutation($quantity, $inventoryId, $arguments);
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function decreaseStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory
    {
        return $this->createStockMutation(-1 * abs($quantity), $inventoryId, $arguments);
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function clearStock(?int $inventoryId = null, ?int $newQuantity = null, array $arguments = []): bool
    {
        $this->inventoryHistories()->delete();

        if ($inventoryId && $newQuantity) {
            $this->createStockMutation($newQuantity, $inventoryId, $arguments);
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function setStock(int $newQuantity, int $inventoryId, array $arguments = []): ?InventoryHistory
    {
        $currentStock = $this->stock;
        $deltaStock = $newQuantity - $currentStock;

        if (! $deltaStock) {
            return null;
        }

        return $this->createStockMutation($deltaStock, $inventoryId, $arguments);
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function createStockMutation(int $quantity, int $inventoryId, array $arguments = []): InventoryHistory
    {
        $reference = Arr::get($arguments, 'reference');

        $createArguments = collect([
            'quantity' => $quantity,
            'old_quantity' => Arr::get($arguments, 'old_quantity'),
            'description' => Arr::get($arguments, 'description'),
            'event' => Arr::get($arguments, 'event'),
            'inventory_id' => $inventoryId,
            'user_id' => Arr::get($arguments, 'user_id', Auth::id()),
        ])->when($reference, fn ($collection) => $collection
            ->put('reference_type', $reference->getMorphClass())
            ->put('reference_id', $reference->getKey()))
            ->toArray();

        return $this->inventoryHistories()->create($createArguments);
    }

    /**
     * @return MorphMany<InventoryHistory, $this>
     */
    public function inventoryHistories(): MorphMany
    {
        return $this->morphMany(InventoryHistory::class, 'stockable');
    }

    protected function stock(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                if (array_key_exists('real_stock', $this->attributes)) {
                    return (int) $this->attributes['real_stock'];
                }

                if (static::$preventLazyStockLoading) {
                    throw new LazyStockLoadingException(static::class);
                }

                return $this->getStock();
            },
        );
    }
}
