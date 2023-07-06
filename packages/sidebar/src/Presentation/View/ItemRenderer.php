<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation\View;

use Illuminate\Contracts\View\View;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Presentation\AbstractRenderer;
use Shopper\Sidebar\Presentation\ActiveStateChecker;

final class ItemRenderer extends AbstractRenderer
{
    protected string $view = 'sidebar::item';

    public function render(Item $item): ?View
    {
        if ($item->isAuthorized()) {
            $items = [];
            foreach ($item->getItems() as $child) {
                $items[] = (new ItemRenderer($this->factory))->render($child);
            }

            $badges = [];
            foreach ($item->getBadges() as $badge) {
                $badges[] = (new BadgeRenderer($this->factory))->render($badge);
            }

            $appends = [];
            foreach ($item->getAppends() as $append) {
                $appends[] = (new AppendRenderer($this->factory))->render($append);
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
