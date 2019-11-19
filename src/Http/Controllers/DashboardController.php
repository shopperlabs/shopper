<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Display Shopper Dashboard
     *
     * @return mixed
     */
    public function index()
    {
        return view('shopper::dashboard.index');
    }
}
