<?php

namespace Shopper\Framework\Listeners;

use Shopper\Framework\Events\StoreCreated;
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
     * @param  StoreCreated  $event
     * @return void
     */
    public function handle(StoreCreated $event)
    {
        $this->repository->create([
            'name' => $name = __('Web Store'),
            'slug' => str_slug($name),
            'url' => env('APP_URL'),
            'is_default' => true,
        ]);
    }
}
