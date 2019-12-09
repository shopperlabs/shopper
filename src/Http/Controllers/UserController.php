<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\UserRepository;

class UserController extends Controller
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
        return view('shopper::pages.user.index');
    }
}
