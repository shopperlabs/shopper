<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Contracts\View\View;
use Shopper\Framework\Models\Shop\Inventory\Inventory;

class InventoryController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_inventories');

        return view('shopper::pages.settings.inventories.index');
    }

    public function create(): View
    {
        $this->authorize('add_inventories');

        return view('shopper::pages.settings.inventories.create');
    }

    public function edit(Inventory $inventory): View
    {
        $this->authorize('edit_inventories');

        return view('shopper::pages.settings.inventories.edit', compact('inventory'));
    }
}
