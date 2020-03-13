<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Shopper\Framework\Http\Requests\ShopRequest;
use Shopper\Framework\Repositories\Shop\ShopRepository;

class ShopController extends Controller
{
    /**
     * @var ShopRepository
     */
    protected ShopRepository $repository;

    /**
     * ShopController constructor.
     *
     * @param  ShopRepository $repository
     */
    public function __construct(ShopRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Store a new Shop in database.
     *
     * @param  ShopRequest $request
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
}
