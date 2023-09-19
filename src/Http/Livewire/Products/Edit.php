<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Products;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryRepository;

class Edit extends Component
{
    public Model $product;

    public Collection $inventories;

    public int $inventory;

    protected $listeners = ['productHasUpdated'];

    public function mount($product): void
    {
        $this->product = $product;
        $this->inventories = $inventories = (new InventoryRepository())->get();
        $this->inventory = $inventories->firstWhere('is_default', true)->id;
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
