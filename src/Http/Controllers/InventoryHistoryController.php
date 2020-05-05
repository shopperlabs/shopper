<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class InventoryHistoryController extends Controller
{
    /**
     * Display Inventory Index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.inventories.index');
    }
}
