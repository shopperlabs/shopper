<?php

namespace Shopper\Framework\Listeners;

use Shopper\Framework\Events\ShopCreated;
use Shopper\Framework\Repositories\ChannelRepository;

class ChannelSubscriber
{
    /**
     * @var ChannelRepository
     */
    protected ChannelRepository $repository;

    /**
     * Create the event listener.
     *
     * @param  ChannelRepository $repository
     */
    public function __construct(ChannelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the event.
     *
     * @param  ShopCreated $event
     * @return void
     */
    public function handle(ShopCreated $event)
    {
        $this->repository->create([
            'name' => $name = __('Web Store'),
            'slug' => str_slug($name),
            'url' => env('APP_URL'),
            'shop_id' => $event->shop->id,
        ]);
    }
}
