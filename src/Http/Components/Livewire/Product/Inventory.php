<?php

namespace Shopper\Framework\Http\Components\Livewire\Product;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryHistoryRepository;
use Shopper\Framework\Repositories\InventoryRepository;

class Inventory extends Component
{
    public $product_id;
    public int $stock;
    public string $value = "0";
    public int $realStock = 0;
    public $inventories;
    public $inventory = 0;

    public function mount(int $productId)
    {
        $product = (new ProductRepository())->getById($productId);
        $this->inventories = (new InventoryRepository())->get(['name', 'id']);
        $this->inventory = (new InventoryRepository())->where('is_default', true)->first()->id;

        $this->product_id = $product->id;
        $this->stock = $product->stock;
        $this->realStock = $product->stock;
    }

    private function getProduct()
    {
        return (new ProductRepository())
            ->with('inventoryHistories')
            ->getById($this->product_id);
    }

    public function updatedValue($value)
    {
        $this->value = $value;
        $this->realStock = $this->stock + (int) $this->value;
    }

    public function incrementStock()
    {
        $this->validate(['value' => 'required']);
        (int) $this->value++;
        $this->realStock = $this->stock + (int) $this->value;
    }

    public function decrementStock()
    {
        $this->validate(['value' => 'required']);
        (int) $this->value--;
        $this->realStock = $this->stock + (int) $this->value;
    }

    public function updateCurrentStock()
    {
        $product = $this->getProduct();

        if ($this->value !== 0) {
            if ($this->realStock >= $this->stock) {
                $product->increaseStock(
                    auth()->user()->shop->id,
                    $this->inventory,
                    $this->value,
                    [
                        'event' => __('Manually added'),
                        'old_quantity' => $this->value,
                    ]
                );
            } else {
                $product->decreaseStock(
                    auth()->user()->shop->id,
                    $this->inventory,
                    $this->value,
                    [
                        'event' => __('Manually removed'),
                        'old_quantity' => $this->value,
                    ]
                );
            }

            $this->value = 0;
            $this->realStock = $this->stock = $product->stock;
            session()->flash('success', __("Stock successfully Updated"));
        }
    }

    public function render()
    {
        $product = $this->getProduct();
        $histories = (new InventoryHistoryRepository())
            ->where('inventory_id', $this->inventory)
            ->where('stockable_id', $product->id)
            ->get();

        return view('shopper::components.livewire.products.inventory', compact('product', 'histories'));
    }
}
