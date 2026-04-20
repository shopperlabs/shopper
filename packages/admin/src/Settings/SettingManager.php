<?php

declare(strict_types=1);

namespace Shopper\Settings;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Shopper\Contracts\SettingItem;
use Shopper\Enum\SettingGroup;

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
     * @return Collection<int, array{group: ?string, label: ?string, items: Collection<int, SettingItem>}>
     */
    public function grouped(): Collection
    {
        $items = $this->all();

        /** @var Collection<int, array{group: ?string, label: ?string, items: Collection<int, SettingItem>}> $buckets */
        $buckets = collect();

        collect(SettingGroup::cases())
            ->sortBy(fn (SettingGroup $group): int => $group->order())
            ->each(function (SettingGroup $group) use ($items, $buckets): void {
                $groupItems = $items
                    ->filter(fn (SettingItem $item): bool => $item->group() === $group->value)
                    ->values();

                if ($groupItems->isNotEmpty()) {
                    $buckets->push([
                        'group' => $group->value,
                        'label' => $group->getLabel(),
                        'items' => $groupItems,
                    ]);
                }
            });

        $customGroups = $items
            ->map(fn (SettingItem $item): ?string => $item->group())
            ->filter(fn (?string $group): bool => $group !== null && SettingGroup::tryFrom($group) === null)
            ->unique()
            ->sort()
            ->values();

        foreach ($customGroups as $customGroup) {
            $groupItems = $items
                ->filter(fn (SettingItem $item): bool => $item->group() === $customGroup)
                ->values();

            $buckets->push([
                'group' => $customGroup,
                'label' => Str::headline($customGroup),
                'items' => $groupItems,
            ]);
        }

        $ungrouped = $items
            ->filter(fn (SettingItem $item): bool => $item->group() === null)
            ->values();

        if ($ungrouped->isNotEmpty()) {
            $buckets->push([
                'group' => null,
                'label' => null,
                'items' => $ungrouped,
            ]);
        }

        /** @phpstan-ignore return.type */
        return $buckets;
    }

    /**
     * @return array<class-string<SettingItem>, bool>
     */
    public function registered(): array
    {
        return $this->settings;
    }
}
