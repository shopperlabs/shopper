<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;

abstract class AbstractRenderer
{
    public function __construct(protected Factory $factory)
    {
    }
}
