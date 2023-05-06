<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Contracts\View\View;
use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;

class BrandController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_brands');

        return view('shopper::pages.brands.index');
    }

    public function create(): View
    {
        $this->authorize('add_brands');

        return view('shopper::pages.brands.create');
    }

    public function edit(int $id): View
    {
        $this->authorize('edit_brands');

        return view('shopper::pages.brands.edit', [
            'brand' => (new BrandRepository())->getById($id),
        ]);
    }
}
