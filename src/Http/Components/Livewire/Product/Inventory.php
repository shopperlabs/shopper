<?php

namespace Shopper\Framework\Http\Components\Livewire\Product;

use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\InventoryRepository;

class Inventory extends Component
{
    public $product_id;
    public int $stock;
    public int $value = 0;
    public int $realStock = 0;

    public function mount(int $productId)
    {
        $product = (new ProductRepository())
            ->getById($productId);

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

    public function updatedValue()
    {
        $this->validate(['value' => 'integer']);
    }

    public function incrementStock()
    {
        $this->validate(['value' => 'integer']);
        $this->value++;
        $this->realStock = $this->stock + $this->value;
    }

    public function decrementStock()
    {
        $this->validate(['value' => 'integer']);
        $this->value--;
        $this->realStock = $this->stock + $this->value;
    }

    public function updateCurrentStock()
    {
        $product = $this->getProduct();
        $defaultInventory = (new InventoryRepository())->where('is_default', true)->first();

        if ($this->realStock >= $this->stock) {
            $product->increaseStock(
                auth()->user()->shop->id,
                $defaultInventory->id,
                $this->realStock,
                [
                    'event' => 'Manually added',
                    'old_quantity' => $this->value,
                ]
            );
        } else {
            $product->decreaseStock(
                auth()->user()->shop->id,
                $defaultInventory->id,
                $this->realStock,
                [
                    'event' => 'Manually removed',
                    'old_quantity' => $this->value,
                ]
            );
        }

        $this->value = 0;
        session()->flash('success', __("Stock successfully Updated"));
    }

    public function render()
    {
        $product = $this->getProduct();

        $stock = $product->stock;
        $realStock = $this->stock + $this->value;

        return view('shopper::components.livewire.products.inventory',
            compact(
                'product',
                'stock',
                'realStock'
            )
        );
    }
}
