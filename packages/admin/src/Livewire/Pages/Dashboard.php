<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Dashboard extends Component
{
    public function render(): View
    {
        return view('shopper::livewire.pages.dashboard')
            ->layout('shopper::components.layouts.app', [
                'title' => __('shopper::layout.sidebar.dashboard'),
            ]);
    }
}
