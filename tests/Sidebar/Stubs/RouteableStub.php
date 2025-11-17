<?php

declare(strict_types=1);

namespace Tests\Sidebar\Stubs;

use Illuminate\Container\Container;
use Shopper\Sidebar\Traits\RouteableTrait;

final class RouteableStub
{
    use RouteableTrait;

    public function __construct(private Container $container) {}
}
