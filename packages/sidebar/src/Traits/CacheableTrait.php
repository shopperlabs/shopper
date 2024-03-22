<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Traits;

trait CacheableTrait
{
    public function serialize(): string
    {
        $cacheables = [];
        foreach ($this->getCacheables() as $cacheable) {
            $cacheables[$cacheable] = $this->{$cacheable};
        }

        return serialize($cacheables);
    }

    public function unserialize(string $serialized): void
    {
        $data = unserialize($serialized);

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function getCacheables(): array
    {
        return $this->cacheables ?? ['menu'];
    }
}
