<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Shopper\Models\Category as BaseCategory;

final class Category extends BaseCategory
{
    public function customMethod(): string
    {
        return 'custom-method-called';
    }
}
