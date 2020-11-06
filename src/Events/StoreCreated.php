<?php

namespace Shopper\Framework\Events;

use Illuminate\Queue\SerializesModels;

class StoreCreated
{
    use SerializesModels;

    /**
     * Setting Model with shop configuration.
     *
     * @var bool
     */
    public $isDefaultInventory;

    /**
     * Create a new event instance.
     *
     * @param  bool  $isDefaultInventory
     */
    public function __construct(bool $isDefaultInventory)
    {
        $this->isDefaultInventory = $isDefaultInventory;
    }
}
