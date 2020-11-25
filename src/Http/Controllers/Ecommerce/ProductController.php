<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductController extends ShopperBaseController
{
    /**
     * Return products list view.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('browse_products');

        return view('shopper::pages.products.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create_products');

        return view('shopper::pages.products.create');
    }

    /**
     * Display Edit view.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $id)
    {
        $this->authorize('edit_products');

        return view('shopper::pages.products.edit', [
            'product' => (new ProductRepository())
                ->with('inventoryHistories')
                ->getById($id)
        ]);
    }
}
