<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\Ecommerce\CustomerRepository;

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
     * Display Edit form.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $customer = (new CustomerRepository())->getById($id);

        return view('shopper::pages.customers.edit', compact('customer'));
    }
}
