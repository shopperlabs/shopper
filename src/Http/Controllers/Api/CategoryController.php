<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Http\Resources\Category as CategoryResource;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * Return JSON list of categories.
     *
     * @return CategoryResource
     */
    public function index()
    {
        return new CategoryResource($this->repository->all());
    }
}
