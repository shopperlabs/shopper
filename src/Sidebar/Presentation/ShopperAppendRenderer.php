<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\Append;

class ShopperAppendRenderer
{
    protected string $view = 'shopper::sidebar.append';

    public function __construct(protected Factory $factory)
    {
    }

    public function render(Append $append): View
    {
        if ($append->isAuthorized()) {
            return $this->factory->make($this->view, [
                'append' => $append,
            ])->render();
        }
    }
}
