<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
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

    public function mutateStock(
        int $inventoryId,
        int $quantity = 1,
        int $oldQuantity = 0,
        ?string $event = null,
        ?string $description = null,
        ?int $userId = null,
        ?Model $reference = null,
    ): InventoryHistory;

    public function decreaseStock(
        int $inventoryId,
        int $quantity = 1,
        int $oldQuantity = 0,
        ?string $event = null,
        ?string $description = null,
        ?int $userId = null,
        ?Model $reference = null,
    ): InventoryHistory;

    public function clearStock(
        ?int $inventoryId = null,
        ?int $newQuantity = null,
        int $oldQuantity = 0,
        ?string $event = null,
        ?string $description = null,
        ?int $userId = null,
        ?Model $reference = null,
    ): bool;

    public function setStock(
        int $newQuantity,
        int $inventoryId,
        int $oldQuantity = 0,
        ?string $event = null,
        ?string $description = null,
        ?int $userId = null,
        ?Model $reference = null,
    ): ?InventoryHistory;

    public function createStockMutation(
        int $quantity,
        int $inventoryId,
        int $oldQuantity = 0,
        ?string $event = null,
        ?string $description = null,
        ?int $userId = null,
        ?Model $reference = null,
    ): InventoryHistory;

    /**
     * @return MorphMany<InventoryHistory, $this>
     */
    public function inventoryHistories(): MorphMany;
}
