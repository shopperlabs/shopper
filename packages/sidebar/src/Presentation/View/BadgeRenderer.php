<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation\View;

use Illuminate\Contracts\View\View;
use Shopper\Sidebar\Contracts\Builder\Badge;
use Shopper\Sidebar\Presentation\AbstractRenderer;

final class BadgeRenderer extends AbstractRenderer
{
    protected string $view = 'sidebar::badge';

    public function render(Badge $badge): ?View
    {
        if ($badge->isAuthorized()) {
            return $this->factory->make($this->view, [
                'badge' => $badge,
            ])->render();
        }

        return null;
    }
}
