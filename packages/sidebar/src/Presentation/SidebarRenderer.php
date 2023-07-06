<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Illuminate\Contracts\View\View;
use Shopper\Sidebar\Contracts\Sidebar;

interface SidebarRenderer
{
    public function render(Sidebar $sidebar): ?View;
}
