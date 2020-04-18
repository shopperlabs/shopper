<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shopper\Framework\Events\ProductCreated;
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
        $published_at = now();

        if ($request->input('date')) {
            $published_at = Carbon::createFromFormat('Y-m-d H:i', $request->input('date').' '.($request->input('time') ?? now()->format('H:i')))->toDateTimeString();
        }

        $product = $this->repository->create([
            'name' => $request->input('name'),
            'sku' => $request->input('sku'),
            'barcode' => $request->input('barcode'),
            'security_stock' => $request->input('security_stock'),
            'description' => $request->input('body'),
            'price' => $request->input('price'),
            'brand_id' => $request->input('brand_id'),
            'shop_id' => auth()->user()->shop->id,
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'backorder' => $request->input('backorder') === "true" ? true : false,
            'requires_shipping' => $request->input('requires_shipping') === "true" ? true : false,
            'published_at' => $published_at,
        ]);

        if ($request->input('media_id') !== "0") {
            $media = $this->mediaRepository->getById($request->input('media_id'));
            $media->update([
                'mediatable_type'   => config('shopper.models.product'),
                'mediatable_id'     => $product->id
            ]);
        }

        if ($request->input('categories')) {
            $product->categories()->sync($request->input('categories'));
        }

        if ($request->input('collections')) {
            $product->collections()->sync($request->input('collections'));
        }

        event(new ProductCreated($product, (int) $request->input('quantity')));

        notify()->success(__('Product Successfully Created.'));

        return redirect()->route('shopper.products.edit', $product);
    }

    /**
     * Display Edit view.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $product = $this->repository->with('images')->getById($id);
        $brands = $this->brandRepository->pluck('name', 'id');
        $categories = $this->categoryRepository->pluck('name', 'id');
        $collections = $this->collectionRepository->pluck('name', 'id');

        return view('shopper::pages.products.edit', compact('product', 'brands', 'categories', 'collections'));
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
