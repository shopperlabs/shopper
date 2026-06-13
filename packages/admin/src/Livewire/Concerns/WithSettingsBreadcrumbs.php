<?php

declare(strict_types=1);

namespace Shopper\Livewire\Concerns;

use BackedEnum;
use Filament\Support\Contracts\ScalableIcon;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Route;
use Shopper\Contracts\SettingItem;
use Shopper\Navigation\Setting\SettingManager;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Breadcrumbs\BreadcrumbLink;
use Shopper\Sidebar\Breadcrumbs\Breadcrumbs;

trait WithSettingsBreadcrumbs
{
    public function mountWithSettingsBreadcrumbs(): void
    {
        $registry = resolve(Breadcrumbs::class);

        foreach ($this->getSettingsBreadcrumbs() as $crumb) {
            $registry->push($crumb);
        }
    }

    /**
     * @return list<Breadcrumb>
     */
    public function getSettingsBreadcrumbs(): array
    {
        return [
            $this->settingsRootCrumb(),
            ...$this->settingsPageBreadcrumbs(),
        ];
    }

    /**
     * Override to append page-specific crumbs after the Settings root.
     *
     * @return list<Breadcrumb>
     */
    public function settingsPageBreadcrumbs(): array
    {
        return [];
    }

    public function settingsRootCrumb(): Breadcrumb
    {
        return new Breadcrumb(
            text: __('shopper::pages/settings/global.menu'),
            url: Route::has('shopper.settings.shop')
                ? route('shopper.settings.shop')
                : null,
            icon: 'phosphor-faders',
            links: $this->settingsDropdownLinks(),
        );
    }

    /**
     * @return list<BreadcrumbLink>
     */
    public function settingsDropdownLinks(): array
    {
        return resolve(SettingManager::class)
            ->all()
            ->filter(
                fn (SettingItem $setting): bool => $setting->permission() === null
                    || shopper()->auth()->user()?->can($setting->permission())
            )
            ->map(fn (SettingItem $setting): BreadcrumbLink => new BreadcrumbLink(
                text: $setting->name(),
                url: $setting->url() ?? '#',
                icon: $this->resolveSettingIcon($setting->icon()),
            ))
            ->values()
            ->all();
    }

    protected function resolveSettingIcon(string|BackedEnum $icon): ?string
    {
        if (is_string($icon)) {
            return $icon;
        }

        if ($icon instanceof ScalableIcon) {
            return $icon->getIconForSize(IconSize::Small);
        }

        return $icon->value;
    }
}
