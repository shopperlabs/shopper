<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Models\Shop\Inventory\Inventory;

class DeleteInventory extends ModalComponent
{
    public int $inventoryId;

    public string $name;

    public function mount(int $inventoryId, string $name): void
    {
        $this->inventoryId = $inventoryId;
        $this->name = $name;
    }

    public function delete(): void
    {
        Inventory::query()->find($this->inventoryId)->delete();

        session()->flash('success', __('Inventory Successfully removed.'));

        $this->redirectRoute('shopper.settings.inventories.index');
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.delete-inventory');
    }
}
