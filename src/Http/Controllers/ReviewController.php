<?php

namespace Shopper\Framework\Http\Controllers;

use Shopper\Framework\Models\Shop\Review;

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

    /**
     * Display review show page.
     *
     * @param  Review  $review
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Review $review)
    {
        $this->authorize('browse_products');

        return view('shopper::pages.reviews.show', compact('review'));
    }
}
