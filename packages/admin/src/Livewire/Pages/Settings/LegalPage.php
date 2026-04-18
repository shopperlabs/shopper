<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Models\Legal;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class LegalPage extends Component
{
    use HandlesAuthorizationExceptions;
    use WithSettingsBreadcrumbs;

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/settings/global.legal.title')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('system.settings');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.legal', [
            'legals' => Legal::query()->get()->mapWithKeys(fn (Legal $item): array => [$item->slug => $item]),
        ])
            ->title(__('shopper::pages/settings/global.legal.title'));
    }
}
