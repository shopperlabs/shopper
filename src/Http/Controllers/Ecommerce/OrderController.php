<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Shopper\Framework\Http\Controllers\ShopperBaseController;

class OrderController extends ShopperBaseController
{
    /**
     * Return orders list view.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('browse_orders');

        return view('shopper::pages.orders.index');
    }
}
