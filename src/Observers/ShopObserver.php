<?php

namespace Shopper\Framework\Observers;

use Shopper\Framework\Models\Shop\Shop;

class ShopObserver
{
    /**
     * Trigger Before Create a Shop
     *
     * @param  Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function creating(Shop $shop)
    {
        $shop->owner_id = auth()->user()->id;
    }
}
