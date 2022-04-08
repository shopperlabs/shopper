<?php

namespace Shopper\Framework\Http\Composers;

use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Shopper\Framework\Sidebar\AdminSidebar;

class SidebarCreator
{
    protected AdminSidebar $sidebar;

    protected SidebarRenderer $renderer;

    public function __construct(AdminSidebar $sidebar, SidebarRenderer $renderer)
    {
        $this->sidebar = $sidebar;
        $this->renderer = $renderer;
    }

    /**
     * @param $view
     */
    public function create($view)
    {
        $view->sidebar = $this->renderer->render($this->sidebar);
    }
}
