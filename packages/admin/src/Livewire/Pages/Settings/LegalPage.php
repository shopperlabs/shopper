<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Models\Legal;

#[Layout('shopper::components.layouts.setting')]
class LegalPage extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.legal', [
            'legals' => Legal::query()->get()->mapWithKeys(function (Legal $item) {
                return [$item->slug => $item];
            }),
        ])
            ->title(__('shopper::pages/settings.legal.title'));
    }
}
