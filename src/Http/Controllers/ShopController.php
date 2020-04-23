<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Money\Currencies\ISOCurrencies;
use Shopper\Framework\Http\Requests\UpdateShopRequest;
use Shopper\Framework\Models\Shop\Shop;
use Shopper\Framework\Repositories\MediaRepository;
use Shopper\Framework\Repositories\Shop\ShopRepository;

class ShopController extends Controller
{
    /**
     * @var ShopRepository
     */
    protected ShopRepository $repository;

    /**
     * @var MediaRepository
     */
    protected MediaRepository $mediaRepository;

    public function __construct(ShopRepository $repository, MediaRepository $mediaRepository)
    {
        $this->repository = $repository;
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Display Shop Initialization view.
     *
     * @return \Illuminate\View\View
     */
    public function initialize()
    {
        return view('shopper::pages.shop.initialize');
    }

    /**
     * Display Shop Setting view.
     *
     * @return \Illuminate\View\View
     */
    public function setting()
    {
        $store = auth()->user()->shop;
        $currencies = new ISOCurrencies();

        return view('shopper::pages.shop.setting', compact('store', 'currencies'));
    }

    /**
     * Update Shop information.
     *
     * @param  UpdateShopRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateShopRequest $request, $id)
    {
        $shop = $this->repository->updateById($id, $request->only([
            'name',
            'phone_number',
            'email',
            'description',
            'address',
            'country',
            'city',
            'post_code'
        ]));

        if ($request->input('media_id') !== "0") {

            // Get the current Media
            $media = $this->mediaRepository->getById($request->input('media_id'));

            if ($shop->previewImage && $shop->previewImage->id !== (int) $request->input('media_id')) {
                // Remove media from the given brand
                $shop->previewImage()->delete();

                $media->update([
                    'mediatable_type'   => $this->repository->model(),
                    'mediatable_id'     => $shop->id
                ]);

                $shop->update(['cover_url' => $media->file_url]);
            }

            $media->update([
                'mediatable_type'   => $this->repository->model(),
                'mediatable_id'     => $shop->id
            ]);

            $shop->update(['cover_url' => $media->file_url]);
        }

        notify()->success(__('Store Successfully Updated!'));

        return redirect()->route('shopper.shop.setting');
    }
}
