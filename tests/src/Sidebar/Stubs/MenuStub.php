<?php

declare(strict_types=1);

namespace Shopper\Tests\Sidebar\Stubs;

use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Domain\DefaultMenu;

final class MenuStub extends DefaultMenu implements Menu {}
