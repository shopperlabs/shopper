<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\DiscountRepository;

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

    /**
     * Display edit view.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $discount = (new DiscountRepository())->getById($id);

        return view('shopper::pages.discounts.edit', compact('discount'));
    }
}