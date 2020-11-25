<?php

namespace Shopper\Framework\Http\Controllers;

class InventoryHistoryController extends ShopperBaseController
{
    /**
     * Display Inventory History Index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.inventories.index');
    }
}
