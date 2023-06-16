<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers\Ecommerce;

use Illuminate\Contracts\View\View;
use Shopper\Core\Repositories\Ecommerce\CollectionRepository;
use Shopper\Http\Controllers\ShopperBaseController;

class CollectionController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_collections');

        return view('shopper::pages.collections.index');
    }

    public function create(): View
    {
        $this->authorize('add_collections');

        return view('shopper::pages.collections.create');
    }

    public function edit(int $id): View
    {
        $this->authorize('edit_collections');

        return view('shopper::pages.collections.edit', [
            'collection' => (new CollectionRepository())->getById($id),
        ]);
    }
}
