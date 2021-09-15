<?php

namespace Shopper\Framework\Sidebar\Presentation;

use Maatwebsite\Sidebar\Group;
use Illuminate\Contracts\View\Factory;

class ShopperGroupRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'shopper::sidebar.group';

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function render(Group $group)
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
