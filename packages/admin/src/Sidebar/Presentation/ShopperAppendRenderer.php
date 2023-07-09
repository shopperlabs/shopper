<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation;

use Shopper\Sidebar\Presentation\View\AppendRenderer;

final class ShopperAppendRenderer extends AppendRenderer
{
    protected string $view = 'shopper::sidebar.append';
}
