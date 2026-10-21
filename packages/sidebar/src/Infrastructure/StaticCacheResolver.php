<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Carbon;
use Shopper\Sidebar\Contracts\Sidebar;

final class StaticCacheResolver implements SidebarResolver
{
    public function __construct(
        protected ContainerResolver $resolver,
        protected Cache $cache,
        protected Config $config,
        protected Translator $translator,
        protected SidebarSerializer $serializer
    ) {}

    public function resolve(string $name): Sidebar
    {
        $key = CacheKey::get($name);
        $locale = $this->translator->getLocale();
        $payloads = $this->cache->get($key, []);

        if (! isset($payloads[$locale])) {
            $minutes = $this->config->get('sidebar.cache.duration');
            $payloads[$locale] = $this->serializer->serialize($this->resolver->resolve($name));

            $this->cache->put($key, $payloads, $minutes === null ? null : Carbon::now()->addMinutes((int) $minutes));
        }

        return $this->serializer->unserialize($payloads[$locale]);
    }
}
