<?php

namespace Shopper\Sidebar\Presentation;

use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Presentation\View\ItemRenderer;

final class ShopperItemRenderer extends ItemRenderer
{
    protected string $view = 'shopper::sidebar.item';

    public function render(Item $item): ?string
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

        return null;
    }
}
