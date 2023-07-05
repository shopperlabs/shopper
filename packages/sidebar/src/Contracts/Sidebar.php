<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts;

use Shopper\Sidebar\Contracts\Builder\Menu;

interface Sidebar
{
    public function build();

    public function getMenu(): Menu;
}
