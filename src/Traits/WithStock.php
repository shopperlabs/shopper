<?php

namespace Shopper\Framework\Traits;

use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Shopper\Framework\Exports\ProductInventoryExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait WithStock
{
    public int $stock = 0;

    public int $value = 0;

    public int $realStock = 0;

    public mixed $inventory;

    public function updatedValue(int $value): void
    {
        $this->value = $value;
        $this->realStock = $this->stock + $this->value;
    }

    public function incrementStock(): void
    {
        $this->validate(['value' => 'required|integer']);

        $this->value++;
        $this->realStock = $this->stock + $this->value;
    }

    public function decrementStock(): void
    {
        if ($this->realStock === 0) {
            return;
        }

        $this->validate(['value' => 'required|integer']);

        $this->value--;
        $this->realStock = $this->stock + $this->value;
    }

    public function updateCurrentStock(): void
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
            'message' => __('Stock successfully Updated'),
        ]);
    }

    public function export(): BinaryFileResponse|Response
    {
        return (new ProductInventoryExport())
            ->forProduct($this->product->id)
            ->download('product-stock-movements.xlsx', Excel::XLSX);
    }
}
