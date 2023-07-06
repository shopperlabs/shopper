<?php

declare(strict_types=1);

namespace Shopper\Tests\Sidebar\Stubs;

use Illuminate\Container\Container;
use Shopper\Sidebar\Traits\RouteableTrait;

class RouteableStub
{
    use RouteableTrait;

    public function __construct(private Container $container)
    {
    }
}
