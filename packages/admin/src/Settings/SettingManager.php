<?php

declare(strict_types=1);

namespace Shopper\Settings;

use Illuminate\Support\Collection;
use Shopper\Contracts\SettingItem;

final class SettingManager
{
    /** @var array<class-string<SettingItem>, bool> */
    private array $settings = [];

    /**
     * @param  array<class-string<SettingItem>, bool>  $settings
     */
    public function register(array $settings): self
    {
        $this->settings = array_merge($this->settings, $settings);

        return $this;
    }

    /**
     * @param  class-string<SettingItem>  $setting
     */
    public function add(string $setting, bool $enabled = true): self
    {
        $this->settings[$setting] = $enabled;

        return $this;
    }

    /**
     * @param  class-string<SettingItem>  $setting
     */
    public function enable(string $setting): self
    {
        if (array_key_exists($setting, $this->settings)) {
            $this->settings[$setting] = true;
        }

        return $this;
    }

    /**
     * @param  class-string<SettingItem>  $setting
     */
    public function disable(string $setting): self
    {
        if (array_key_exists($setting, $this->settings)) {
            $this->settings[$setting] = false;
        }

        return $this;
    }

    /**
     * @return Collection<int, SettingItem>
     */
    public function all(): Collection
    {
        return collect($this->settings)
            ->filter(fn (bool $enabled): bool => $enabled)
            ->keys()
            ->map(fn (string $class): SettingItem => app($class))
            ->sortBy(fn (SettingItem $setting): int => $setting->order())
            ->values();
    }

    /**
     * @return array<class-string<SettingItem>, bool>
     */
    public function registered(): array
    {
        return $this->settings;
    }
}
