<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

interface SidebarFlusher
{
    public function flush(string $name): void;
}
