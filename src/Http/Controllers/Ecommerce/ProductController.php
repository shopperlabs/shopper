<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductController extends ShopperBaseController
{
    public function index()
    {
        $this->authorize('browse_products');

        return view('shopper::pages.products.index');
    }

    public function create()
    {
        $this->authorize('add_products');

        return view('shopper::pages.products.create');
    }

    public function edit(int $id)
    {
        $this->authorize('edit_products');

        return view('shopper::pages.products.edit', [
            'product' => (new ProductRepository())
                ->with(['inventoryHistories', 'variations', 'categories', 'collections', 'channels', 'relatedProducts', 'attributes'])
                ->getById($id),
        ]);
    }

    public function variant(int $product, int $id)
    {
        $this->authorize('edit_products');

        return view('shopper::pages.products.variant', [
            'product' => (new ProductRepository())->getById($product),
            'variant' => (new ProductRepository())
                ->with('inventoryHistories')
                ->getById($id),
        ]);
    }
}
