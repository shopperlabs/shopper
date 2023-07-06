<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation\View;

use Illuminate\Contracts\View\View;
use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Presentation\AbstractRenderer;

final class AppendRenderer extends AbstractRenderer
{
    protected string $view = 'sidebar::append';

    public function render(Append $append): ?View
    {
        if ($append->isAuthorized()) {
            return $this->factory->make($this->view, [
                'append' => $append,
            ])->render();
        }

        return null;
    }
}
