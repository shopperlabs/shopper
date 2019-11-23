<?php

namespace Shopper\Framework\Http\Composers;

use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Shopper\Framework\Sidebar\AdminSidebar;

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

    /**
     * @param AdminSidebar    $sidebar
     * @param SidebarRenderer $renderer
     */
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
