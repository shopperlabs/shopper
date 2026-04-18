<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Traits;

use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\Breadcrumbs;

trait WithBreadcrumbs
{
    public function mountWithBreadcrumbs(): void
    {
        $registry = resolve(Breadcrumbs::class);

        foreach ($this->getBreadcrumbs() as $crumb) {
            $registry->push($crumb);
        }
    }

    /**
     * @return list<Breadcrumb>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }
}
