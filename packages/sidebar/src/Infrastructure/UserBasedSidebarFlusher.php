<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

use Illuminate\Contracts\Cache\Repository as Cache;

final class UserBasedSidebarFlusher implements SidebarFlusher
{
    public function __construct(protected Cache $cache)
    {
    }

    public function flush(string $name): void
    {
        if ((new SupportsCacheTags())->isSatisfiedBy($this->cache)) {
            $this->cache->tags(CacheKey::get($name))->flush();
        }
    }
}
