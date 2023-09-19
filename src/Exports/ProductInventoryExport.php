<?php

declare(strict_types=1);

namespace Shopper\Framework\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Shopper\Framework\Models\Shop\Inventory\InventoryHistory;

class ProductInventoryExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public int $productId;

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

    public function map($row): array
    {
        return [
            $row->id,
            $row->stockable->name,
            $row->quantity,
            $row->event,
            $row->inventory->name,
            $row->user->full_name,
            $row->created_at->formatLocalized('%d %B, %Y'),
        ];
    }

    public function forProduct(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function query(): Builder
    {
        return InventoryHistory::query()->where('stockable_id', $this->productId);
    }
}
