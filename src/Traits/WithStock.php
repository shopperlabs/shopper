<?php

namespace Shopper\Framework\Traits;

use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Shopper\Framework\Exports\ProductInventoryExport;
use Shopper\Framework\Repositories\InventoryRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait WithStock
{
    /**
     * Stock product value
     *
     * @var int
     */
    public $stock;

    /**
     * @var int
     */
    public $value = 0;

    /**
     * @var int
     */
    public $realStock = 0;

    /**
     * All locations available on the store.
     *
     * @var mixed
     */
    public $inventories;

    /**
     * Default inventory id.
     *
     * @var int
     */
    public $inventory;

    /**
     * Update stock value.
     *
     * @param  int  $value
     */
    public function updatedValue($value)
    {
        $this->value = $value;
        $this->realStock = $this->stock + (int) $this->value;
    }

    /**
     * Increment product stock.
     *
     * @return void
     */
    public function incrementStock()
    {
        $this->validate(['value' => 'required|integer']);

        $this->value++;
        $this->realStock = $this->stock + (int) $this->value;
    }

    /**
     * Decrement product stock.
     *
     * @return void
     */
    public function decrementStock()
    {
        if ($this->realStock === 0) {
            return;
        }

        $this->validate(['value' => 'required|integer']);

        $this->value--;
        $this->realStock = $this->stock + (int) $this->value;
    }

    /**
     * Update stock.
     *
     * @return void
     */
    public function updateCurrentStock()
    {
        if ($this->value === 0) {
            return;
        }

        $this->validate(['value' => 'required|integer']);

        if ($this->realStock >= $this->stock) {
            $this->product->increaseStock(
                $this->inventory,
                $this->value,
                [
                    'event' => __('Manually added'),
                    'old_quantity' => $this->value,
                ]
            );
        } else {
            $this->product->decreaseStock(
                $this->inventory,
                $this->value,
                [
                    'event' => __('Manually removed'),
                    'old_quantity' => $this->value,
                ]
            );
        }

        $this->value = 0;
        $this->realStock = $this->stock = $this->product->stock;

        $this->notify([
            'title' => __('Updated'),
            'message' => __("Stock successfully Updated"),
        ]);
    }

    /**
     * Export default product stock movement.
     *
     * @return Response|BinaryFileResponse
     */
    public function export()
    {
        return (new ProductInventoryExport())
            ->forProduct($this->product->id)
            ->download('product-stock-movements.xlsx', Excel::XLSX);
    }
}
