<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\Group;

class ShopperGroupRenderer
{
    protected string $view = 'shopper::sidebar.group';

    public function __construct(protected Factory $factory)
    {
    }

    public function render(Group $group): View
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
    }
}
