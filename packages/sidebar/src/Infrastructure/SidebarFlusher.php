<?php

namespace Shopper\Sidebar\Infrastructure;

interface SidebarFlusher
{
    public function flush(string $name): void;
}
