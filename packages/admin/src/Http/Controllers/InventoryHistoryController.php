<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Contracts\View\View;

class InventoryHistoryController extends ShopperBaseController
{
    public function index(): View
    {
        return view('shopper::pages.inventories.index');
    }
}
