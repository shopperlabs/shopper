<?php

declare(strict_types=1);

namespace Tests\Sidebar\Stubs;

use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Domain\DefaultGroup;

final class GroupStub extends DefaultGroup implements Group {}
