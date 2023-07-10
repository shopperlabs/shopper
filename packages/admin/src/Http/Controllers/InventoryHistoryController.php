<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers;

use Illuminate\Contracts\View\View;

final class InventoryHistoryController extends ShopperBaseController
{
    public function index(): View
    {
        return view('shopper::pages.inventories.index');
    }
}
