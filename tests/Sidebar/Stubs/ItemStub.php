<?php

declare(strict_types=1);

namespace Tests\Sidebar\Stubs;

use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Domain\DefaultItem;

final class ItemStub extends DefaultItem implements Item {}
