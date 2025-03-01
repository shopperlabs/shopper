<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

final class CategoryRepository extends Repository
{
    public function model(): string
    {
        return config('shopper.models.category');
    }
}
