<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        return view('shopper::pages.reviews.index');
    }
}
