<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Requests\Ecommerce\CollectionRequest;
use Shopper\Framework\Repositories\Ecommerce\CollectionRepository;
use Shopper\Framework\Repositories\MediaRepository;

class CollectionController extends Controller
{
    /**
     * @var CollectionRepository
     */
    protected CollectionRepository $repository;

    /**
     * @var MediaRepository
     */
    protected MediaRepository $mediaRepository;

    /**
     * collectionController constructor.
     *
     * @param  CollectionRepository $repository
     * @param  MediaRepository $mediaRepository
     */
    public function __construct(CollectionRepository $repository, MediaRepository $mediaRepository)
    {
        $this->repository = $repository;
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Return collections list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $collections = $this->repository->paginate(25);

        return view('shopper::pages.collections.index', compact('collections'));
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('shopper::pages.collections.create');
    }

    /**
     * Store a newly collection to the database.
     *
     * @param  CollectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CollectionRequest $request)
    {
        $datetime = null;

        if ($request->input('date')) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->input('date').' '.($request->input('time') ?? now()->format('H:i')))->toDateTimeString();
        }

        $collection = $this->repository->create([
            'name' => $request->input('name'),
            'description' => $request->input('body'),
            'type' => $request->input('type'),
            'published_at' => $datetime,
        ]);

        if ($request->input('media_id') !== "0") {
            $media = $this->mediaRepository->getById($request->input('media_id'));
            $media->update([
                'mediatable_type'   => config('shopper.models.collection'),
                'mediatable_id'     => $collection->id,
            ]);
        }

        notify()->success(__('Collection Successfully Created'));

        return redirect()->route('shopper.collections.index');
    }

    /**
     * Display Edit form.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $collection = $this->repository->getById($id);

        return view('shopper::pages.collections.edit', compact('collection'));
    }

    /**
     * Update collection.
     *
     * @param  CollectionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CollectionRequest $request, $id)
    {
        $datetime = null;

        if ($request->input('date')) {
            $datetime = Carbon::createFromFormat('Y-m-d H:i', $request->input('date').' '.($request->input('time') ?? now()->format('H:i')))->toDateTimeString();
        }

        $collection = $this->repository->updateById($id, [
            'name' => $request->input('name'),
            'description' => $request->input('body'),
            'type' => $request->input('type'),
            'published_at' => $datetime,
        ]);

        if ($request->input('media_id') !== "0") {

            // Get the current Media
            $media = $this->mediaRepository->getById($request->input('media_id'));
            // dd($collection->previewImage);

            if ($collection->previewImage && $collection->previewImage->id !== (int) $request->input('media_id')) {
                // Remove media from the given collection
                $collection->previewImage()->delete();

                $media->update([
                    'mediatable_type'   => config('shopper.models.collection'),
                    'mediatable_id'     => $collection->id,
                ]);
            }

            $media->update([
                'mediatable_type'   => config('shopper.models.collection'),
                'mediatable_id'     => $collection->id,
            ]);
        }

        notify()->success(__('Collection Successfully Updated'));

        return redirect()->route('shopper.collections.index');
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
            notify()->success(__('Collection deleted successfully'));

            if ($request->isXmlHttpRequest()) {
                return response()->json(['redirect_url' => route('shopper.collections.index')]);
            }

            return redirect()->route('shopper.collections.index');
        } catch (\Exception $e) {
            notify()->error(__("We can't delete this collection!"));

            return back();
        }
    }
}
