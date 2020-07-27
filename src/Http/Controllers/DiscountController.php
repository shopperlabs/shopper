<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class DiscountController extends Controller
{
    /**
     * Display Discount Index.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.discounts.index');
    }

    /**
     * Display Create Discount View.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.discounts.create');
    }
}