<?php

namespace Shopper\Framework\Listeners\Products;

use Shopper\Framework\Events\Products\ProductCreated;

class CreateProductSubscriber
{
    /**
     * Handle the event.
     *
     * @param  ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
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
