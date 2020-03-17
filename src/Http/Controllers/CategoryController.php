<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return Categories list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = $this->repository->paginate(25);

        return view('shopper::pages.categories.index', compact('categories'));
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
}
