<?php

declare(strict_types=1);

namespace Shopper\Theme;

use Illuminate\Support\Collection;

final class ThemeManager
{
    /** @var array<string, Theme> */
    private array $themes = [];

    public function register(Theme $theme): self
    {
        $this->themes[$theme->id] = $theme;

        return $this;
    }

    /**
     * @param  array<string, array{name?: string, author?: string, has_dark?: bool, css?: string}>  $themes
     */
    public function registerMany(array $themes): self
    {
        foreach ($themes as $id => $attributes) {
            $this->register(Theme::fromArray($id, $attributes));
        }

        return $this;
    }

    /**
     * @return Collection<int, Theme>
     */
    public function all(): Collection
    {
        return collect($this->themes)->values();
    }

    public function has(string $id): bool
    {
        return isset($this->themes[$id]);
    }

    public function find(string $id): ?Theme
    {
        return $this->themes[$id] ?? null;
    }

    public function default(): Theme
    {
        $id = config('shopper.themes.default', 'default');

        return $this->find(is_string($id) ? $id : 'default')
            ?? $this->all()->first()
            ?? new Theme(id: 'default', name: 'Default');
    }

    public function active(): Theme
    {
        $id = shopper_setting('admin_theme');

        if (is_string($id) && $this->has($id)) {
            return $this->find($id);
        }

        return $this->default();
    }
}
