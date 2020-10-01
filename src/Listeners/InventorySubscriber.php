<?php

namespace Shopper\Framework\Listeners;

use Shopper\Framework\Events\ShopCreated;
use Shopper\Framework\Repositories\InventoryRepository;

class InventorySubscriber
{
    /**
     * @var InventoryRepository
     */
    protected InventoryRepository $repository;

    /**
     * Create the event listener.
     *
     * @param  InventoryRepository $repository
     */
    public function __construct(InventoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  ShopCreated  $event
     * @return void
     */
    public function handle(ShopCreated $event)
    {
        $this->repository->create([
           'name' => $event->shop->name,
           'code' => str_slug($event->shop->name),
           'email' => $event->shop->email,
           'phone_number' => $event->shop->phone_number,
           'is_default' => true,
        ]);
    }
}
