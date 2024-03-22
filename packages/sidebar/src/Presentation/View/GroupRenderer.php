<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation\View;

use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Presentation\AbstractRenderer;

class GroupRenderer extends AbstractRenderer
{
    protected string $view = 'sidebar::group';

    public function render(Group $group): ?string
    {
        if ($group->isAuthorized()) {
            $items = [];
            foreach ($group->getItems() as $item) {
                $items[] = (new ItemRenderer($this->factory))->render($item);
            }

            return $this->factory->make($this->view, [
                'group' => $group,
                'items' => $items,
            ])->render();
        }

        return null;
    }
}
