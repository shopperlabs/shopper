<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Repositories\UserRepository;

class CustomerController extends ShopperBaseController
{
    /**
     * Return customers list view.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('browse_customers');

        return view('shopper::pages.customers.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create_customers');

        return view('shopper::pages.customers.create');
    }

    /**
     * Display Show view.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('read_customers');

        return view('shopper::pages.customers.show', [
            'customer' => (new UserRepository())->getById($id)
        ]);
    }
}
