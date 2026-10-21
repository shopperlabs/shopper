<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

final class NullSidebarFlusher implements SidebarFlusher
{
    public function flush(string $name): void {}
}
