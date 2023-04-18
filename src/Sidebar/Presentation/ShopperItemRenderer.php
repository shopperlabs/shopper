<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Presentation\ActiveStateChecker;

class ShopperItemRenderer
{
    protected string $view = 'shopper::sidebar.item';

    public function __construct(protected Factory $factory)
    {
    }

    /**
     * @return \Illuminate\Contracts\View\View|void
     */
    public function render(Item $item)
    {
        if ($item->isAuthorized()) {
            $items = [];
            foreach ($item->getItems() as $child) {
                $items[] = (new self($this->factory))->render($child);
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
