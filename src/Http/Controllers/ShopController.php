<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class ShopController extends Controller
{
    /**
     * Display Shop Initialization view
     *
     * @return \Illuminate\View\View
     */
    public function initialize()
    {
        return view('shopper::pages.shop.initialize');
    }

    /**
     * Display Shop Setting view
     *
     * @return \Illuminate\View\View
     */
    public function setting()
    {
        return view('shopper::pages.shop.setting');
    }
}
