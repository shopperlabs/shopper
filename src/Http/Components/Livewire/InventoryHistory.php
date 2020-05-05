<?php

namespace Shopper\Framework\Http\Components\Livewire;

use Livewire\Component;
use Shopper\Framework\Repositories\InventoryHistoryRepository;

class InventoryHistory extends Component
{
    /**
     * Search.
     *
     * @var string
     */
    public $search = '';

    /**
     * Sort direction.
     *
     * @var string
     */
    public $direction = 'desc';

    /**
     * Sort results.
     *
     * @param  string  $value
     */
    public function sort($value)
    {
        $this->direction = $value === 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        $collections = (new InventoryHistoryRepository())
            ->with(['stockable'])
            ->orderBy('created_at', $this->direction)
            ->get();

        $inventoryHistories = $collections
            ->map(function ($item, $key) {
                $item['name'] = $item->stockable->name ?? "N/A";
                $item['sku'] = $item->stockable->sku ?? __("No SKU");
                $item['route_name'] = $item->stockable->route_name ?? null;

                return $item;
            });

        return view('shopper::components.livewire.inventories.list', compact('inventoryHistories'));
    }
}
