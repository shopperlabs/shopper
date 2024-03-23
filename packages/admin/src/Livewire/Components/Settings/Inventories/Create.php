<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Inventories;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Shopper\Contracts\HasForm;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Inventory;
use Shopper\Livewire\AbstractBaseComponent;

class Create extends AbstractBaseComponent implements HasForm
{
    use UseForm;

    public string $model = Inventory::class;

    public function store(): void
    {
        $this->save($this->model);

        session()->flash('success', __('shopper::pages/settings.notifications.create_inventory'));

        $this->redirectRoute('shopper.settings.inventories');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.inventories.create', [
            'countries' => Cache::get(
                key: 'countries',
                default: fn () => Country::query()
                    ->select('name', 'id')
                    ->orderBy('name')
                    ->get()
            ),
        ]);
    }
}
