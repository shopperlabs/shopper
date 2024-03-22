<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation\View;

use Illuminate\Contracts\View\View;
use Shopper\Sidebar\Contracts\Sidebar;
use Shopper\Sidebar\Presentation\AbstractRenderer;
use Shopper\Sidebar\Presentation\SidebarRenderer as BaseSidebarRenderer;

class SidebarRenderer extends AbstractRenderer implements BaseSidebarRenderer
{
    protected string $view = 'sidebar::menu';

    public function render(Sidebar $sidebar): ?View
    {
        $menu = $sidebar->getMenu();

        if ($menu->isAuthorized()) {
            $groups = [];
            foreach ($menu->getGroups() as $group) {
                $groups[] = (new GroupRenderer($this->factory))->render($group);
            }

            return $this->factory->make($this->view, [
                'groups' => $groups,
            ]);
        }

        return null;
    }
}
