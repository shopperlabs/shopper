<?php

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Group;

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

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Group $group
     *
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
