<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Settings\Inventories;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Shopper\Contracts\HasForm;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\Inventory;
use Shopper\Http\Livewire\AbstractBaseComponent;

class Edit extends AbstractBaseComponent implements HasForm
{
    use UseForm;

    public Inventory $inventory;

    public function mount(Inventory $inventory): void
    {
        $this->inventory = $inventory;
        $this->inventoryId = $inventory->id;
        $this->name = $inventory->name;
        $this->email = $inventory->email;
        $this->description = $inventory->description;
        $this->street_address = $inventory->street_address;
        $this->street_address_plus = $inventory->street_address_plus;
        $this->country_id = $inventory->country_id;
        $this->city = $inventory->city;
        $this->zipcode = $inventory->zipcode;
        $this->isDefault = $inventory->is_default;
        $this->phone_number = $inventory->phone_number;
    }

    public function store(): void
    {
        $this->save($this->inventory);

        session()->flash('success', __('shopper::pages/settings.notifications.update_inventory'));

        $this->redirectRoute('shopper.settings.inventories');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.inventories.edit', [
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
