<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Presentation\View\GroupRenderer;

final class ShopperGroupRenderer extends GroupRenderer
{
    protected string $view = 'shopper::sidebar.group';

    public function render(Group $group): ?string
    {
        if ($group->isAuthorized()) {
            $items = [];
            foreach ($group->getItems() as $item) {
                $items[] = (new ShopperItemRenderer($this->factory))->render($item);
            }

            return $this->factory->make($this->view, [
                'group' => $group,
                'items' => $items,
            ])->render();
        }

        return null;
    }
}
