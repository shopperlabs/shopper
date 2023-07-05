<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

final class ChannelRepository extends BaseRepository
{
    public function model(): string
    {
        return config('shopper.models.channel');
    }
}
