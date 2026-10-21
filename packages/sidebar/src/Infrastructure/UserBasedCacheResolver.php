<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Infrastructure;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Carbon;
use Shopper\Sidebar\Contracts\Sidebar;

final class UserBasedCacheResolver implements SidebarResolver
{
    public function __construct(
        protected ContainerResolver $resolver,
        protected Cache $cache,
        protected Auth $auth,
        protected Config $config,
        protected Translator $translator,
        protected SidebarSerializer $serializer
    ) {}

    public function resolve(string $name): Sidebar
    {
        if (! (new SupportsCacheTags)->isSatisfiedBy($this->cache)) {
            return $this->resolver->resolve($name);
        }

        $minutes = $this->config->get('sidebar.cache.duration');

        $payload = $this->cache->tags(CacheKey::get($name))->remember(
            CacheKey::get($name, $this->auth->guard()->id().'.'.$this->translator->getLocale()),
            $minutes === null ? null : Carbon::now()->addMinutes((int) $minutes),
            fn (): string => $this->serializer->serialize($this->resolver->resolve($name))
        );

        return $this->serializer->unserialize($payload);
    }
}
