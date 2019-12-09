<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Display Shopper Dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.dashboard.index');
    }
}
