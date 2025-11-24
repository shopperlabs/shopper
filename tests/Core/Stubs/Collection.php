<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Shopper\Core\Models\Collection as BaseCollection;

final class Collection extends BaseCollection
{
    public function customMethod(): string
    {
        return 'custom-method-called';
    }
}
