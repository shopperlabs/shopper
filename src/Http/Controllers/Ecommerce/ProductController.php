<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Shopper\Framework\Events\ProductCreated;
use Shopper\Framework\Http\Requests\Ecommerce\ProductRequest;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class ProductController extends Controller
{
    /**
     * Return products list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.products.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.products.create');
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
        return view('shopper::pages.products.edit', [
            'product' => (new ProductRepository())->with('inventoryHistories')->getById($id)
        ]);
    }
}
