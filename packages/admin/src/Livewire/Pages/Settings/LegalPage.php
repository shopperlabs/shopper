<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Shopper\Core\Models\Legal;
use Shopper\Livewire\Components\Settings\Legal\PolicyForm;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class LegalPage extends Component
{
    use HandlesAuthorizationExceptions;
    use WithSettingsBreadcrumbs;

    #[Url(as: 'tab', except: '')]
    public string $activeTab = 'privacy';

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
        $legals = Legal::query()
            ->get()
            ->mapWithKeys(fn (Legal $item): array => [$item->slug => $item]);

        return view('shopper::livewire.pages.settings.legal', [
            'legals' => $legals,
            'tabs' => $this->buildTabs($legals),
        ])
            ->title(__('shopper::pages/settings/global.legal.title'));
    }

    /**
     * @param  Collection<string, Legal>  $legals
     * @return array<int, array<string, string>>
     */
    private function buildTabs(Collection $legals): array
    {
        return $legals
            ->map(fn (Legal $legal, string $slug): array => [
                'key' => $slug,
                'label' => __("shopper::pages/settings/global.legal." . (PolicyForm::SLUG_TRANSLATION_KEYS[$slug] ?? $slug)),
            ])
            ->values()
            ->all();
    }
}
