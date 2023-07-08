<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation\View;

use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Presentation\AbstractRenderer;

class BadgeRenderer extends AbstractRenderer
{
    protected string $view = 'sidebar::badge';

    public function render(Badge $badge): ?string
    {
        if ($badge->isAuthorized()) {
            return $this->factory->make($this->view, [
                'badge' => $badge,
            ])->render();
        }

        return null;
    }
}
