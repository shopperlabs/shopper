<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Models\Contracts\ShopperUser;

#[Layout('shopper::components.layouts.base')]
final class Initialization extends Component
{
    public function mount(): void
    {
        $user = auth()->user();

        abort_unless(
            $user instanceof ShopperUser
                && ($user->isAdmin() || $user->can('access_setting')),
            403,
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.initialization')
            ->title(__('shopper::pages/onboarding.title'));
    }
}
