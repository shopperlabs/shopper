<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Sidebar\Traits\WithBreadcrumbs;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Account extends AbstractPageComponent
{
    use HandlesAuthorizationExceptions;
    use WithBreadcrumbs;

    public function getBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/auth.account.title')),
        ];
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.account')
            ->title(__('shopper::pages/auth.account.meta_title'));
    }
}
