<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * Display Categories resource view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.categories.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.categories.create');
    }

    /**
     * Display Edit form.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('shopper::pages.categories.edit', [
            'category' => (new CategoryRepository())->getById($id)
        ]);
    }
}
