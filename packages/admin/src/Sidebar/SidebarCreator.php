<?php

declare(strict_types=1);

namespace Shopper\Sidebar;

use Shopper\Sidebar\Presentation\SidebarRenderer;

final class SidebarCreator
{
    public function __construct(protected AdminSidebar $sidebar, protected SidebarRenderer $renderer) {}

    public function create($view): void
    {
        $view->sidebar = $this->renderer->render($this->sidebar);
    }
}
