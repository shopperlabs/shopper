<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Composers;

use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Shopper\Framework\Sidebar\AdminSidebar;

class SidebarCreator
{
    public function __construct(protected AdminSidebar $sidebar, protected SidebarRenderer $renderer)
    {
    }

    public function create($view): void
    {
        $view->sidebar = $this->renderer->render($this->sidebar);
    }
}
