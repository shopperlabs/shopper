<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\UserRepository;

class CustomerController extends Controller
{
    /**
     * Return customers list view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.customers.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.customers.create');
    }

    /**
     * Display Show view.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('shopper::pages.customers.show', [
            'customer' => (new UserRepository())->getById($id)
        ]);
    }
}
