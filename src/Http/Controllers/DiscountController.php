<?php

namespace Shopper\Framework\Http\Controllers;

use Shopper\Framework\Repositories\DiscountRepository;

class DiscountController extends ShopperBaseController
{
    /**
     * Display Discount Index.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('browse_discounts');

        return view('shopper::pages.discounts.index');
    }

    /**
     * Display Create Discount View.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('add_discounts');

        return view('shopper::pages.discounts.create');
    }

    /**
     * Display edit view.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $id)
    {
        $this->authorize('edit_discounts');

        return view('shopper::pages.discounts.edit', [
            'discount' => (new DiscountRepository())->getById($id),
        ]);
    }
}
