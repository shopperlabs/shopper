<?php

namespace Shopper\Framework\Models\Traits;

use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Shopper\Framework\Models\InventoryHistory;

trait HasStock
{
    /**
     * Stock accessor.
     *
     * @return int
     */
    public function getStockAttribute()
    {
        return $this->stock();
    }

    /**
     * Return the current stock.
     *
     * @param  null|DateTimeInterface  $date
     * @return int
     */
    public function stock($date = null)
    {
        $date = $date ?: Carbon::now();

        if (!$date instanceof DateTimeInterface) {
            $date = Carbon::create($date);
        }

        return (int) $this->inventoryHistories()
            ->where('created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->sum('quantity');
    }

    /**
     * Return the current stock of the inventory.
     *
     * @param  int  $inventory_id
     * @param  null|string  $date
     * @return int
     */
    public function stockInventory($inventory_id, $date = null)
    {
        $date = $date ?: Carbon::now();

        if (!$date instanceof DateTimeInterface) {
            $date = Carbon::create($date);
        }

        return (int) $this->inventoryHistories()
            ->where('created_at', '<=', $date->format('Y-m-d H:i:s'))
            ->where('inventory_id', $inventory_id)
            ->sum('quantity');
    }

    /**
     * Increase Stock for an item.
     *
     * @param  int  $inventory_id
     * @param  int  $quantity
     * @param  array  $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function increaseStock($inventory_id, $quantity = 1, $arguments = [])
    {
        return $this->createStockMutation($quantity, $inventory_id, $arguments);
    }

    /**
     * Decrease Stock for an item.
     *
     * @param  int  $inventory_id
     * @param  int  $quantity
     * @param  array  $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function decreaseStock($inventory_id, $quantity = 1, $arguments = [])
    {
        return $this->createStockMutation(-1 * abs($quantity), $inventory_id, $arguments);
    }

    /**
     * Mutate Stock for an item.
     *
     * @param  int  $inventory_id
     * @param  int  $quantity
     * @param  array  $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function mutateStock($inventory_id, $quantity = 1, $arguments = [])
    {
        return $this->createStockMutation($quantity, $inventory_id, $arguments);
    }

    /**
     * Reset a Stock for the Shop and inventory.
     *
     * @param  null|int  $newQuantity
     * @param  int  $inventory_id
     * @param  array  $arguments
     * @return bool
     */
    public function clearStock($inventory_id, $newQuantity = null,  $arguments = [])
    {
        $this->inventoryHistories()->delete();

        if (!is_null($newQuantity)) {
            $this->createStockMutation($newQuantity, $inventory_id, $arguments);
        }

        return true;
    }

    /**
     * Return stock statement if the item is still in stock.
     *
     * @param  int  $quantity
     * @return bool
     */
    public function inStock($quantity = 1)
    {
        return $this->stock > 0 && $this->stock >= $quantity;
    }

    /**
     * Set a new stock for the item. We will specify in which inventory
     * will be allocated this stock for the item.
     *
     * @param  int  $newQuantity
     * @param  int  $inventory_id
     * @param  array  $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setStock($newQuantity, $inventory_id, $arguments = [])
    {
        $currentStock = $this->stock;

        if ($deltaStock = $newQuantity - $currentStock) {
            return $this->createStockMutation($deltaStock, $inventory_id, $arguments);
        }
    }

    /**
     * Internal function to handle mutations (increase, decrease).
     * We create a stock mutation for a shop and for a specific inventory.
     *
     * @param  int  $quantity
     * @param  int  $inventory_id
     * @param  array  $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createStockMutation($quantity, $inventory_id, $arguments = [])
    {
        $reference = Arr::get($arguments, 'reference');

        $createArguments = collect([
            'quantity'      => $quantity,
            'old_quantity'  => Arr::get($arguments, 'old_quantity'),
            'description'   => Arr::get($arguments, 'description'),
            'event'         => Arr::get($arguments, 'event'),
            'inventory_id'  => $inventory_id,
            'user_id'       => auth()->id(),
        ])->when($reference, function ($collection) use ($reference) {
            return $collection
                ->put('reference_type', $reference->getMorphClass())
                ->put('reference_id', $reference->getKey());
        })->toArray();

        return $this->inventoryHistories()->create($createArguments);
    }

    /**
     * Relation with InventoryHistory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function inventoryHistories()
    {
        return $this->morphMany(InventoryHistory::class, 'stockable')->orderBy('created_at', 'desc');
    }
}
