<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\Badge;

class ShopperBadgeRenderer
{
    protected string $view = 'shopper::sidebar.badge';

    public function __construct(protected Factory $factory)
    {
    }

    public function render(Badge $badge): View
    {
        if ($badge->isAuthorized()) {
            return $this->factory->make($this->view, [
                'badge' => $badge,
            ])->render();
        }
    }
}
