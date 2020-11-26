<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Shopper\Framework\Exceptions\ShopperExceptionHandler;

class ShopperBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * ShopperBaseController new instance
     * Define a custom ExceptionHandler for shopper request.
     *
     * @return void.
     */
    public function __construct()
    {
        if (Auth::check()) {
            app()->singleton(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                ShopperExceptionHandler::class
            );
        }
    }
}
