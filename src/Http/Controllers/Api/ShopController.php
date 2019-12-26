<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Requests\ShopRequest;
use Shopper\Framework\Repositories\Shop\ShopRepository;

class ShopController extends Controller
{
    /**
     * @var ShopRepository
     */
    protected $repository;

    public function __construct(ShopRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store Shop
     *
     * @param ShopRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShopRequest $request)
    {
        $this->repository->create($request->all());

        return response()->json([
            'message' => __("Your Shop has been successfully created. Have fun !"),
            'status'  => 'success'
        ]);
    }

    public function updateSetting()
    {

    }
}
