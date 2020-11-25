<?php

namespace Shopper\Framework\Http\Controllers;

class DashboardController extends ShopperBaseController
{
    /**
     * Display Shopper Dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.dashboard.index');
    }
}
