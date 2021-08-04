<?php

namespace Shopper\Framework\Http\Composers;

use Shopper\Framework\Sidebar\AdminSidebar;
use Maatwebsite\Sidebar\Presentation\SidebarRenderer;

class SidebarCreator
{
    /**
     * @var AdminSidebar
     */
    protected $sidebar;

    /**
     * @var SidebarRenderer
     */
    protected $renderer;

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
