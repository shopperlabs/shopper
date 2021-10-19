<?php

namespace Shopper\Framework\Models\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\morphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Shopper\Framework\Models\Shop\Inventory\InventoryHistory;

trait HasStock
{
    /**
     * Stock accessor.
     */
    public function getStockAttribute(): int
    {
        return $this->stock();
    }

    /**
     * Return the current stock.
     */
    public function stock(?DateTimeInterface $date = null): int
    {
        $date = $date ? $date : Carbon::now();

        if (! $date instanceof DateTimeInterface) {
            $date = Carbon::create($date);
        }

        return (int) $this->inventoryHistories()
            ->where('created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->sum('quantity');
    }

    /**
     * Return the current stock of the inventory.
     */
    public function stockInventory(int $inventoryId, ?string $date = null): int
    {
        $date = $date ? $date : Carbon::now();

        if (! $date instanceof DateTimeInterface) {
            $date = Carbon::create($date);
        }

        return (int) $this->inventoryHistories()
            ->where('created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->where('inventory_id', $inventoryId)
            ->sum('quantity');
    }

    /**
     * Increase Stock for an item.
     */
    public function increaseStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory
    {
        return $this->createStockMutation($quantity, $inventoryId, $arguments);
    }

    /**
     * Decrease Stock for an item.
     */
    public function decreaseStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory
    {
        return $this->createStockMutation(-1 * abs($quantity), $inventoryId, $arguments);
    }

    /**
     * Mutate Stock for an item.
     */
    public function mutateStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory
    {
        return $this->createStockMutation($quantity, $inventoryId, $arguments);
    }

    /**
     * Reset a Stock for the Shop and inventory.
     */
    public function clearStock(int $inventory_id, ?int $newQuantity = null, array $arguments = []): bool
    {
        $this->inventoryHistories()->delete();

        if (null !== $newQuantity) {
            $this->createStockMutation($newQuantity, $inventory_id, $arguments);
        }

        return true;
    }

    /**
     * Return stock statement if the item is still in stock.
     */
    public function inStock(int $quantity = 1): bool
    {
        return $this->stock > 0 && $this->stock >= $quantity;
    }

    /**
     * Set a new stock for the item. We will specify in which inventory
     * will be allocated this stock for the item.
     */
    public function setStock(int $newQuantity, int $inventoryId, array $arguments = []): InventoryHistory
    {
        $currentStock = $this->stock;
        $deltaStock = $newQuantity - $currentStock;

        if ($deltaStock) {
            return $this->createStockMutation($deltaStock, $inventoryId, $arguments);
        }
    }

    public function inventoryHistories(): morphMany
    {
        return $this->morphMany(InventoryHistory::class, 'stockable')->orderBy('created_at', 'desc');
    }

    /**
     * Internal function to handle mutations (increase, decrease).
     * We create a stock mutation for a shop and for a specific inventory.
     */
    protected function createStockMutation(int $quantity, int $inventoryId, array $arguments = []): InventoryHistory
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
}
