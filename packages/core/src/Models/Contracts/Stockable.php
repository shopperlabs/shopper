<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Core\Models\InventoryHistory;

/**
 * @property-read int $stock
 */
interface Stockable
{
    public function inStock(int $quantity = 1): bool;

    public function getStock(string|DateTimeInterface|null $date = null): int;

    public function stockInventory(int $inventoryId, ?string $date = null): int;

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function mutateStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory;

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function decreaseStock(int $inventoryId, int $quantity = 1, array $arguments = []): InventoryHistory;

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function clearStock(?int $inventoryId = null, ?int $newQuantity = null, array $arguments = []): bool;

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function setStock(int $newQuantity, int $inventoryId, array $arguments = []): ?InventoryHistory;

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function createStockMutation(int $quantity, int $inventoryId, array $arguments = []): InventoryHistory;

    /**
     * @return MorphMany<InventoryHistory, $this>
     */
    public function inventoryHistories(): MorphMany;
}
