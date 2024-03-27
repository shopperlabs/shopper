<?php

declare(strict_types=1);

namespace Shopper\Infrastructure;

use Shopper\Sidebar\Infrastructure\SidebarFlusher;

final class NullSidebarFlusher implements SidebarFlusher
{
    public function flush(string $name): void
    {
    }
}
