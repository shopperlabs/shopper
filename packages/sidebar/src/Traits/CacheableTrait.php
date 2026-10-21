<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Traits;

trait CacheableTrait
{
    /**
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        $cacheables = [];
        foreach ($this->getCacheables() as $cacheable) {
            $cacheables[$cacheable] = $this->{$cacheable};
        }

        return $cacheables;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function __unserialize(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    public function unserialize(string $serialized): void
    {
        $this->__unserialize(unserialize($serialized));
    }

    public function getCacheables(): array
    {
        return $this->cacheables ?? ['menu'];
    }
}
