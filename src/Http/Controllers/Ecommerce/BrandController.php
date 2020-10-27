<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class BrandController extends Controller
{
    /**
     * Return brands list view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.brands.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.brands.create');
    }

    /**
     * Display Edit form.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $brand = (new BrandRepository())->getById($id);

        return view('shopper::pages.brands.edit', compact('brand'));
    }
}
