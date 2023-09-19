<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Contracts\View\View;
use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_products');

        return view('shopper::pages.products.index');
    }

    public function create(): View
    {
        $this->authorize('add_products');

        return view('shopper::pages.products.create');
    }

    public function edit(int $id): View
    {
        $this->authorize('edit_products');

        return view('shopper::pages.products.edit', [
            'product' => (new ProductRepository())
                ->with(['inventoryHistories', 'variations', 'categories', 'collections', 'channels', 'relatedProducts', 'attributes'])
                ->getById($id),
        ]);
    }

    public function variant(int $product, int $id): View
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
