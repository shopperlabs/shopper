<?php

declare(strict_types=1);

namespace Shopper\Tests\Sidebar\Stubs;

use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Domain\DefaultAppend;

final class AppendStub extends DefaultAppend implements Append
{
}
