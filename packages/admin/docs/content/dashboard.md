# Dashboard

The dashboard is a user-customizable screen. One of Shopper's main goals is to enable stores to easily customize the modules.

## Overview

When you log into the control panel, you will be taken to the dashboard — a customizable screen dispatch with a Livewire component!

Laravel Shopper is a free open-source e-commerce application based on the [TALL Stack](https://tallstack.dev) and aims to build an e-commerce administration using only [Livewire](https://laravel-livewire.com) components.

The navigation at the left contains the available panels for everyday use:

<div class="screenshot">
    <img src="/screenshots/{{version}}/dashboard.png" alt="Shopper Global Set Example">
    <div class="caption">The dashboard and the Getting Started panel</div>
</div>

Clicking on each icon will open the panel or shows a list of available panels.

## Components

The component that displays the dashboard is quite simple, so you can easily replace it to put your own.

The component used is `Shopper\Livewire\Pages\Dashboard` and can also be found in the components file `config/shopper/components/dashboard.php`.

```php
namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;

class Dashboard extends AbstractPageComponent
{
    public function render(): View
    {
        return view('shopper::livewire.pages.dashboard')
            ->layout('shopper::components.layouts.app', [
                'title' => __('shopper::layout.sidebar.dashboard'),
            ]);
    }
}
```
