<?php

namespace Shopper\Framework\Http\Controllers;

class ReviewController extends ShopperBaseController
{
    /**
     * Display Reviews Index.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('browse_products');

        return view('shopper::pages.reviews.index');
    }
}
