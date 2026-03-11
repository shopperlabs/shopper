<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Traits\HandlesAuthorizationExceptions;

class LocaleSwitcher extends Component
{
    use HandlesAuthorizationExceptions;

    public string $locale;

    public function mount(): void
    {
        $this->locale = app()->getLocale();
    }

    public function switchLocale(string $locale): void
    {
        $available = array_keys(config('shopper.admin.locales', []));

        if (! in_array($locale, $available, strict: true)) {
            return;
        }

        session(['shopper_locale' => $locale]);

        $this->redirect(request()->header('Referer') ?? route('shopper.dashboard'));
    }

    public function render(): View
    {
        return view('shopper::livewire.components.locale-switcher', [
            'locales' => config('shopper.admin.locales', []),
        ]);
    }
}
