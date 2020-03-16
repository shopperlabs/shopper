<?php

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Channel;

class ChannelRepository extends BaseRepository
{
    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Channel::class;
    }
}
