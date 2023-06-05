<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers\Ecommerce;

use Illuminate\Contracts\View\View;
use Shopper\Http\Controllers\ShopperBaseController;
use Shopper\Core\Repositories\Ecommerce\CategoryRepository;

class CategoryController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_categories');

        return view('shopper::pages.categories.index');
    }

    public function create(): View
    {
        $this->authorize('add_categories');

        return view('shopper::pages.categories.create');
    }

    public function edit(int $id): View
    {
        $this->authorize('edit_categories');

        return view('shopper::pages.categories.edit', [
            'category' => (new CategoryRepository())->getById($id),
        ]);
    }
}
