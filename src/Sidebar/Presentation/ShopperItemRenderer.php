<?php

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Presentation\ActiveStateChecker;

class ShopperItemRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'shopper::sidebar.item';

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Item $item
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(Item $item)
    {
        if ($item->isAuthorized()) {
            $items = [];
            foreach ($item->getItems() as $child) {
                $items[] = (new ShopperItemRenderer($this->factory))->render($child);
            }

            $badges = [];
            foreach ($item->getBadges() as $badge) {
                $badges[] = (new ShopperBadgeRenderer($this->factory))->render($badge);
            }

            $appends = [];
            foreach ($item->getAppends() as $append) {
                $appends[] = (new ShopperAppendRenderer($this->factory))->render($append);
            }

            return $this->factory->make($this->view, [
                'item' => $item,
                'items' => $items,
                'badges' => $badges,
                'appends' => $appends,
                'active' => (new ActiveStateChecker())->isActive($item),
            ])->render();
        }
    }
}
