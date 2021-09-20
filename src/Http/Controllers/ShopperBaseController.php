<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShopperBaseController extends Controller
{
    use AuthorizesRequests;

    use DispatchesJobs;

    use ValidatesRequests;
}
