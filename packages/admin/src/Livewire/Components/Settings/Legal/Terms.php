<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Legal;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Legal;

class Terms extends Component
{
    public Legal $legal;

    public function render(): View
    {
        return view('shopper::livewire.components.settings.legal.index', [
            'title' => __('shopper::pages/settings/global.legal.terms_of_use'),
            'description' => __('shopper::pages/settings/global.legal.summary', ['policy' => __('shopper::pages/settings/global.legal.terms_of_use')]),
        ]);
    }
}
