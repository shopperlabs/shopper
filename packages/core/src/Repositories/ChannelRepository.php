<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

class ChannelRepository extends BaseRepository
{
    public function model(): string
    {
        return config('shopper.models.channels');
    }
}
