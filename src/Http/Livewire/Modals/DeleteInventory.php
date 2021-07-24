<?php

namespace Shopper\Framework\Http\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Inventory\Inventory;

class DeleteInventory extends ModalComponent
{
    public int $inventoryId;

    public string $name;

    public function mount(int $inventoryId, string $name)
    {
        $this->inventoryId = $inventoryId;
        $this->name = $name;
    }

    public function delete()
    {
        Inventory::query()->find($this->inventoryId)->delete();

        session()->flash('success', __('Inventory Successfully removed.'));

        $this->redirectRoute('shopper.settings.inventories.index');
    }

    public function render()
    {
        return view('shopper::livewire.modals.delete-inventory');
    }
}
