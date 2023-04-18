<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Badge;

class ShopperBadgeRenderer
{
    protected string $view = 'shopper::sidebar.badge';

    public function __construct(protected Factory $factory)
    {
    }

    /**
     * @return \Illuminate\Contracts\View\View|void
     */
    public function render(Badge $badge)
    {
        if ($badge->isAuthorized()) {
            return $this->factory->make($this->view, [
                'badge' => $badge,
            ])->render();
        }

    }
}
