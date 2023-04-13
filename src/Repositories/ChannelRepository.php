<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Shop\Channel;

class ChannelRepository extends BaseRepository
{
    public function model(): string
    {
        return Channel::class;
    }
}
