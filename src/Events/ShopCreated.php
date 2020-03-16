<?php

namespace Shopper\Framework\Events;

use Illuminate\Queue\SerializesModels;
use Shopper\Framework\Models\Shop\Shop;

class ShopCreated
{
    use SerializesModels;

    /**
     * @var Shop
     */
    public Shop $shop;

    /**
     * Create a new event instance.
     *
     * @param  Shop $shop
     */
    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }
}
