<?php

namespace Shopper\Framework\Sidebar\Presentation;

use Maatwebsite\Sidebar\Sidebar;
use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Presentation\SidebarRenderer;

class ShopperSidebarRenderer implements SidebarRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'shopper::sidebar.menu';

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render(Sidebar $sidebar)
    {
        $menu = $sidebar->getMenu();

        if ($menu->isAuthorized()) {
            $groups = [];
            foreach ($menu->getGroups() as $group) {
                $groups[] = (new ShopperGroupRenderer($this->factory))->render($group);
            }

            return $this->factory->make($this->view, [
                'groups' => $groups,
            ]);
        }
    }
}
