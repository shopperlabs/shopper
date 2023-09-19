<?php

declare(strict_types=1);

namespace Shopper\Framework\Listeners\Products;

use function count;
use Shopper\Framework\Events\Products\ProductCreated;

class CreateProductSubscriber
{
    /**
     * Handle the event.
     */
    public function handle(ProductCreated $event): void
    {
        if (count($event->quantity) > 0) {
            foreach ($event->quantity as $inventory => $quantity) {
                $event->product->mutateStock(
                    $inventory,
                    $event->quantity,
                    [
                        'event' => __('Initial inventory'),
                        'old_quantity' => $quantity,
                    ]
                );
            }
        }
    }
}
