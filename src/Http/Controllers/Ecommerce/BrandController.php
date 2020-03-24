<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Requests\Ecommerce\BrandRequest;
use Shopper\Framework\Repositories\Ecommerce\BrandRepository;
use Shopper\Framework\Repositories\MediaRepository;

class BrandController extends Controller
{
    /**
     * @var BrandRepository
     */
    protected BrandRepository $repository;

    /**
     * @var MediaRepository
     */
    protected MediaRepository $mediaRepository;

    /**
     * brandController constructor.
     *
     * @param  BrandRepository $repository
     * @param  MediaRepository $mediaRepository
     */
    public function __construct(BrandRepository $repository, MediaRepository $mediaRepository)
    {
        $this->repository = $repository;
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Return brands list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $brands = $this->repository->paginate(25);

        return view('shopper::pages.brands.index', compact('brands'));
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.brands.create');
    }

    /**
     * Store a newly brand to the database.
     *
     * @param  BrandRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BrandRequest $request)
    {
        $brand = $this->repository->create([
            'name' => $request->input('name'),
            'description' => $request->input('body'),
        ]);

        if ($request->input('media_id') !== "0") {
            $media = $this->mediaRepository->getById($request->input('media_id'));
            $media->update([
                'mediatable_type'   => config('shopper.models.brand'),
                'mediatable_id'     => $brand->id
            ]);
        }

        notify()->success(__('Brand Successfully Created'));

        return redirect()->route('shopper.brands.index');
    }

    /**
     * Display Edit form.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $brand = $this->repository->getById($id);

        return view('shopper::pages.brands.edit', compact('brand'));
    }

    /**
     * Update brand.
     *
     * @param  BrandRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BrandRequest $request, $id)
    {
        $brand = $this->repository->updateById($id, [
            'name' => $request->input('name'),
            'description' => $request->input('body'),
        ]);

        if ($request->input('media_id') !== "0") {

            // Get the current Media
            $media = $this->mediaRepository->getById($request->input('media_id'));
            // dd($brand->previewImage);

            if ($brand->previewImage && $brand->previewImage->id !== (int) $request->input('media_id')) {
                // Remove media from the given brand
                $brand->previewImage()->delete();

                $media->update([
                    'mediatable_type'   => config('shopper.models.brand'),
                    'mediatable_id'     => $brand->id
                ]);
            }

            $media->update([
                'mediatable_type'   => config('shopper.models.brand'),
                'mediatable_id'     => $brand->id
            ]);
        }

        notify()->success(__('Brand Successfully Updated'));

        return redirect()->route('shopper.brands.index');
    }

    /**
     * Delete a resource on the database.
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->repository->deleteById($id);
            notify()->success(__('Brand deleted successfully'));

            if ($request->isXmlHttpRequest()) {
                return response()->json(['redirect_url' => route('shopper.brands.index')]);
            }

            return redirect()->route('shopper.brands.index');
        } catch (\Exception $e) {
            notify()->error(__("We can't delete this brand!"));

            return back();
        }
    }
}
