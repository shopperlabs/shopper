<?php

namespace Shopper\Framework\Listeners\Products;

use Shopper\Framework\Events\Products\ProductCreated;
use Shopper\Framework\Repositories\InventoryRepository;

class CreateProductSubscriber
{
    /**
     * @var InventoryRepository
     */
    protected InventoryRepository $inventoryRepository;

    /**
     * Create the event listener.
     *
     * @param InventoryRepository $inventoryRepository
     */
    public function __construct(InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        $defaultInventory = $this->inventoryRepository->where('is_default', true)->first();

        if (! $defaultInventory) {
            $defaultInventory = $this->inventoryRepository->first();
        }

        if ($event->quantity > 0) {
            $event->product->mutateStock(
                $defaultInventory->id,
                $event->quantity,
                [
                    'event' => __('Initial inventory'),
                    'old_quantity' => $event->quantity,
                ]
            );
        }
    }
}
