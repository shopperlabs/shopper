<?php

namespace Shopper\Framework\Listeners;

use Shopper\Framework\Events\StoreCreated;
use Shopper\Framework\Models\System\Setting;
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
     * @param  StoreCreated  $event
     * @return void
     */
    public function handle(StoreCreated $event)
    {
        if ($event->isDefaultInventory) {
            $this->repository->create([
               'name' => $name = Setting::query()->where('key', 'shop_name')->first()->value,
               'code' => str_slug($name),
               'email' => Setting::query()->where('key', 'shop_email')->first()->value,
               'street_address' => Setting::query()->where('key', 'shop_street_address')->first()->value,
               'zipcode' => Setting::query()->where('key', 'shop_zipcode')->first(),
               'city' => Setting::query()->where('key', 'shop_city')->first()->value,
               'phone_number' => ($phone = Setting::query()->where('key', 'shop_phone_number')->first()) ? $phone->value: null,
               'country_id' => ($country = Setting::query()->where('key', 'shop_country_id')->first()) ? $country->value: null,
               'longitude' => ($lng = Setting::query()->where('key', 'shop_lng')->first()) ? $lng->value: null,
               'latitude' => ($lat = Setting::query()->where('key', 'shop_lat')->first()) ? $lat->value: null,
               'is_default' => true,
            ]);
        }
    }
}
