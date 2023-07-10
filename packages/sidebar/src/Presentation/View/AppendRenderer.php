<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Presentation\View;

use Shopper\Sidebar\Contracts\Builder\Append;
use Shopper\Sidebar\Presentation\AbstractRenderer;

class AppendRenderer extends AbstractRenderer
{
    protected string $view = 'sidebar::append';

    public function render(Append $append): ?string
    {
        if ($append->isAuthorized()) {
            return $this->factory->make($this->view, [
                'append' => $append,
            ])->render();
        }

        return null;
    }
}
