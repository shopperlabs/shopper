<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Resources\ShopSizes as SizeResource;
use Shopper\Framework\Models\Shop\ShopSize;

class ShopSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        return new SizeResource(ShopSize::all());
    }
}
