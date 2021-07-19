<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class CollectionController extends ShopperBaseController
{
    /**
     * Return collections list view.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('browse_collections');

        return view('shopper::pages.collections.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('add_collections');

        return view('shopper::pages.collections.create');
    }

    /**
     * Display Edit form.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(int $id)
    {
        $this->authorize('edit_collections');

        return view('shopper::pages.collections.edit', [
            'collection' => (new CollectionRepository())->getById($id),
        ]);
    }
}
