<?php

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Shop\Channel;

class ChannelRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     */
    public function model()
    {
        return Channel::class;
    }
}
