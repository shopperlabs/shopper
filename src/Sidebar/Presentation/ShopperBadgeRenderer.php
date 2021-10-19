<?php

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Badge;

class ShopperBadgeRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'shopper::sidebar.badge';

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Illuminate\Contracts\View\View
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
