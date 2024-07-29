<?php

declare(strict_types=1);

namespace Shopper\Tests\Sidebar\Stubs;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Shopper\Sidebar\Contracts\Builder\Itemable;
use Shopper\Sidebar\Traits\CallableTrait;
use Shopper\Sidebar\Traits\ItemableTrait;

final class ItemableStub implements Itemable
{
    use CallableTrait;
    use ItemableTrait;

    public function __construct(private Container $container)
    {
        $this->items = new Collection;
    }
}
