<?php

declare(strict_types=1);

namespace Shopper\Tests\Sidebar\Stubs;

use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Domain\DefaultBadge;

class BadgeStub extends DefaultBadge implements Badge
{
}
