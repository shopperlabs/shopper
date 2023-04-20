<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends ShopperBaseController
{
    public function __invoke(): View
    {
        return view('shopper::pages.dashboard.index');
    }
}
