<?php

namespace Shopper\Framework\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Shopper\Framework\Models\Shop\Inventory\InventoryHistory;

class ProductInventoryExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * Product id.
     *
     * @var int
     */
    public $product;

    public function headings(): array
    {
        return [
            '#',
            __('Product'),
            __('stock'),
            __('Event'),
            __('Inventory'),
            __('User'),
            __('Date'),
        ];
    }

    public function map($inventoryHistory): array
    {
        return [
            $inventoryHistory->id,
            $inventoryHistory->stockable->name,
            $inventoryHistory->quantity,
            $inventoryHistory->event,
            $inventoryHistory->inventory->name,
            $inventoryHistory->user->full_name,
            $inventoryHistory->created_at->formatLocalized('%d %B, %Y'),
        ];
    }

    /**
     * Get product to export all movements.
     *
     * @param  int  $product
     * @return  $this
     */
    public function forProduct(int $product)
    {
        $this->product = $product;

        return $this;
    }

    public function query()
    {
        return InventoryHistory::query()->where('stockable_id', $this->product);
    }
}
