<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Contracts\View\View;
use Shopper\Framework\Models\Shop\Review;

class ReviewController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_products');

        return view('shopper::pages.reviews.index');
    }

    public function show(Review $review): View
    {
        $this->authorize('browse_products');

        return view('shopper::pages.reviews.show', compact('review'));
    }
}
