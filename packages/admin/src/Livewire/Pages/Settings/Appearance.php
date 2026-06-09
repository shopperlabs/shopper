<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Theme\Theme;
use Shopper\Theme\ThemeManager;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\SaveSettings;

#[Layout('shopper::components.layouts.setting')]
class Appearance extends Component
{
    use HandlesAuthorizationExceptions;
    use SaveSettings;
    use WithSettingsBreadcrumbs;

    public string $theme = 'default';

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/settings/appearance.title')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('system.settings');

        $this->theme = resolve(ThemeManager::class)->active()->id;
    }

    /**
     * @return Collection<int, Theme>
     */
    #[Computed]
    public function themes(): Collection
    {
        return resolve(ThemeManager::class)->all();
    }

    public function store(): void
    {
        $this->authorize('system.settings');

        if (! resolve(ThemeManager::class)->has($this->theme)) {
            $this->theme = resolve(ThemeManager::class)->default()->id;
        }

        $this->saveSettings(['admin_theme' => $this->theme]);

        $this->dispatch('theme-saved');

        Notification::make()
            ->title(__('shopper::pages/settings/appearance.saved'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.appearance')
            ->title(__('shopper::pages/settings/appearance.title'));
    }
}
