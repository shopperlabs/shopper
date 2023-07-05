<?php

declare(strict_types=1);

namespace Shopper\Http\Composers;

use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Shopper\Core\Sidebar\AdminSidebar;

final class SidebarCreator
{
    public function __construct(protected AdminSidebar $sidebar, protected SidebarRenderer $renderer)
    {
    }

    public function create($view): void
    {
        $view->sidebar = $this->renderer->render($this->sidebar);
    }
}
