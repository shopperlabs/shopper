<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Shopper\Core\Models\InventoryHistory;

trait HasStock
{
    public function stock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getStock(),
        );
    }

    public function inStock(int $quantity = 1): bool
    {
        return $this->stock > 0 && $this->stock >= $quantity;
    }

    public function getStock(DateTimeInterface $date = null): int
    {
        $date = $date ?: Carbon::now();

        if (! $date instanceof DateTimeInterface) {
            $date = Carbon::create($date);
        }

        return (int) $this->inventoryHistories()
            ->where('created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->sum('quantity');
    }

    public function stockInventory(int $inventoryId, string $date = null): int
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

    public function mutateStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory
    {
        return $this->createStockMutation($quantity, $inventoryId, $arguments);
    }

    public function decreaseStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory
    {
        return $this->createStockMutation(-1 * abs($quantity), $inventoryId, $arguments);
    }

    public function clearStock(int $inventoryId = null, ?int $newQuantity = null, array $arguments = []): bool
    {
        $this->inventoryHistories()->delete();

        if ($inventoryId && $newQuantity) {
            $this->createStockMutation($newQuantity, $inventoryId, $arguments);
        }

        return true;
    }

    public function setStock(int $newQuantity, int $inventoryId, array $arguments = []): ?InventoryHistory
    {
        $currentStock = $this->stock;
        $deltaStock = $newQuantity - $currentStock;

        if (! $deltaStock) {
            return null;
        }

        return $this->createStockMutation($deltaStock, $inventoryId, $arguments);
    }

    public function createStockMutation(int $quantity, int $inventoryId, array $arguments = []): InventoryHistory
    {
        $reference = Arr::get($arguments, 'reference');

        $createArguments = collect([
            'quantity' => $quantity,
            'old_quantity' => Arr::get($arguments, 'old_quantity'),
            'description' => Arr::get($arguments, 'description'),
            'event' => Arr::get($arguments, 'event'),
            'inventory_id' => $inventoryId,
            'user_id' => auth()->id(),
        ])->when($reference, function ($collection) use ($reference) {
            return $collection
                ->put('reference_type', $reference->getMorphClass())
                ->put('reference_id', $reference->getKey());
        })->toArray();

        return $this->inventoryHistories()->create($createArguments);
    }

    public function inventoryHistories(): MorphMany
    {
        return $this->morphMany(InventoryHistory::class, 'stockable')->orderBy('created_at', 'desc');
    }
}
