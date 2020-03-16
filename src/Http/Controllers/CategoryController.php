<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    /**
     * Return Categories list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.categories.index');
    }
}
