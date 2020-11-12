<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;

class CollectionController extends Controller
{
    /**
     * Return collections list view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.collections.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.collections.create');
    }

    /**
     * Display Edit form.
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        return view('shopper::pages.collections.edit', [
            'collection' => (new CollectionRepository())->getById($id)
        ]);
    }
}
