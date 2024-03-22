<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories\Store;

use Shopper\Core\Repositories\BaseRepository;

final class BrandRepository extends BaseRepository
{
    public function model(): string
    {
        return config('shopper.models.brand');
    }
}
