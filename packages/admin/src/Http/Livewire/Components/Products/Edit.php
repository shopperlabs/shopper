<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Repositories\Store\ProductRepository;

class Edit extends Component
{
    public $product;

    public Collection $inventories;

    public ?int $inventory = null;

    protected $listeners = ['productHasUpdated'];

    public function mount($product): void
    {
        $this->product = $product;
        $this->inventories = $inventories = Inventory::all();
        $this->inventory = $inventories->firstWhere('is_default', true)->id ?? $inventories->first()?->id;
    }

    public function productHasUpdated(int $id): void
    {
        $this->product = (new ProductRepository())->getById($id);
    }

    public function render(): View
    {
        return view('shopper::livewire.products.edit', [
            'currency' => shopper_currency(),
        ]);
    }
}
