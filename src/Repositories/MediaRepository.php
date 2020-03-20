<?php

namespace Shopper\Framework\Repositories;

use Shopper\Framework\Models\Media;

class MediaRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model()
    {
        return Media::class;
    }
}
