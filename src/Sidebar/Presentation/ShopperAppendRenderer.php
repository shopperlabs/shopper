<?php

declare(strict_types=1);

namespace Shopper\Framework\Sidebar\Presentation;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Append;

class ShopperAppendRenderer
{
    protected string $view = 'shopper::sidebar.append';

    public function __construct(protected Factory $factory)
    {
    }

    /**
     * @return \Illuminate\Contracts\View\View|void
     */
    public function render(Append $append)
    {
        if ($append->isAuthorized()) {
            return $this->factory->make($this->view, [
                'append' => $append,
            ])->render();
        }
    }
}
