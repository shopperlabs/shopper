<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    /**
     * Display Shop Initialization view.
     *
     * @return \Illuminate\View\View
     */
    public function initialize()
    {
        return view('shopper::pages.settings.initialize');
    }

    /**
     * Display Shop Setting view.
     *
     * @return \Illuminate\View\View
     */
	public function index()
	{
		return view('shopper::pages.settings.index');
	}
}
