<?php

namespace Shopper\Framework\Http\Controllers;

class AnalyticsController extends ShopperBaseController
{
    /**
     * Display Shopper Analytics Dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.analytics.dashboard');
    }
}
