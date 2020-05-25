<?php

namespace Shopper\Framework\Observers;

use Shopper\Framework\Models\Shop\Shop;

class ShopObserver
{
    /**
     * @var string
     */
    protected string $model;

    public function __construct()
    {
        $this->model = config('auth.providers.users.model');
    }

    /**
     * Trigger Before Create a Shop.
     *
     * @param Shop $shop
     * @return void
     */
    public function creating(Shop $shop)
    {
        $shop->owner_id = auth()->check() ?
            auth()->id() :
            tap((new $this->model)->first())
                ->first()
                ->id;
    }
}
