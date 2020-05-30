<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Shopper\Framework\Http\Requests\CustomerRequest;
use Shopper\Framework\Repositories\UserRepository;

class CustomerController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display Resources list view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.customers.index');
    }

    /**
     * Display Create form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.customers.create');
    }

    /**
     * Create a newly customer.
     *
     * @param  CustomerRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CustomerRequest $request)
    {
        $customer = $this->repository->create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'password' => Hash::make($request->input('password')),
            'api_token'    => Str::random(80),
            'email_verified_at' => now()->toDateTimeString(),
        ]);

        $customer->assignRole(config('shopper.users.default_role'));

        notify()->success(__('Customer Successfully Created.'));

        return redirect()->route('shopper.customers.show', $customer);
    }

    /**
     * Display specific resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $customer = $this->repository->with('addresses')->getById($id);

        return view('shopper::pages.customers.show', compact('customer'));
    }
}
