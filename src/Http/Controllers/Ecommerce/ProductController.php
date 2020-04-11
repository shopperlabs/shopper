<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Requests\Ecommerce\ProductRequest;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Repositories\MediaRepository;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected ProductRepository $repository;

    /**
     * @var MediaRepository
     */
    protected MediaRepository $mediaRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CollectionRepository
     */
    protected CollectionRepository $collectionRepository;

    /**
     * @var BrandRepository
     */
    protected BrandRepository $brandRepository;

    /**
     * ProductController constructor.
     *
     * @param  ProductRepository  $repository
     * @param  MediaRepository  $mediaRepository
     * @param  CategoryRepository  $categoryRepository
     * @param  CollectionRepository  $collectionRepository
     * @param  BrandRepository  $brandRepository
     */
    public function __construct(
        ProductRepository $repository,
        MediaRepository $mediaRepository,
        CategoryRepository $categoryRepository,
        CollectionRepository $collectionRepository,
        BrandRepository $brandRepository
    )
    {
        $this->repository = $repository;
        $this->mediaRepository = $mediaRepository;
        $this->categoryRepository = $categoryRepository;
        $this->collectionRepository = $collectionRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * Return products list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = $this->repository->with(['brand', 'shop'])->paginate(25);

        return view('shopper::pages.products.index', compact('products'));
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $brands = $this->brandRepository->pluck('name', 'id');
        $categories = $this->categoryRepository->pluck('name', 'id');
        $collections = $this->collectionRepository->pluck('name', 'id');

        return view('shopper::pages.products.create', compact('brands', 'categories', 'collections'));
    }

    /**
     * Store a newly product to the database.
     *
     * @param  ProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        dd($request->all());
    }

    /**
     * Delete a resource on the database.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->repository->deleteById($id);
            notify()->success(__('Product deleted successfully'));

            if ($request->isXmlHttpRequest()) {
                return response()->json(['redirect_url' => route('shopper.products.index')]);
            }

            return redirect()->route('shopper.products.index');
        } catch (\Exception $e) {
            notify()->error(__("We can't delete this product!"));

            return back();
        }
    }
}
