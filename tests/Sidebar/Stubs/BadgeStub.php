<?php

declare(strict_types=1);

namespace Tests\Sidebar\Stubs;

use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Domain\DefaultBadge;

final class BadgeStub extends DefaultBadge implements Badge {}
