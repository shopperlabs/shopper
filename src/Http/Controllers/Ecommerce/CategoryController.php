<?php

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Requests\Ecommerce\CategoryRequest;
use Shopper\Framework\Repositories\Ecommerce\CategoryRepository;
use Shopper\Framework\Repositories\MediaRepository;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $repository;

    /**
     * @var MediaRepository
     */
    protected MediaRepository $mediaRepository;

    /**
     * CategoryController constructor.
     *
     * @param  CategoryRepository $repository
     * @param  MediaRepository $mediaRepository
     */
    public function __construct(CategoryRepository $repository, MediaRepository $mediaRepository)
    {
        $this->repository = $repository;
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * Return Categories list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('shopper::pages.categories.index');
    }

    /**
     * Display Create view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categories = $this->repository->pluck('name', 'id');

        return view('shopper::pages.categories.create', compact('categories'));
    }

    /**
     * Store a newly category to the database.
     *
     * @param  CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->repository->create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'description' => $request->input('body'),
        ]);

        if ($request->input('media_id') !== "0") {
            $media = $this->mediaRepository->getById($request->input('media_id'));
            $media->update([
                'mediatable_type'   => config('shopper.models.category'),
                'mediatable_id'     => $category->id
            ]);
        }

        notify()->success(__('Category Successfully Created'));

        return redirect()->route('shopper.categories.index');
    }

    /**
     * Display Edit form.
     *
     * @param  int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $category = $this->repository->getById($id);
        $categories = $this->repository->pluck('name', 'id')->except($category->id);

        return view('shopper::pages.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update Category.
     *
     * @param  CategoryRequest $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = $this->repository->updateById($id, [
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'description' => $request->input('body'),
        ]);

        if ($request->input('media_id') !== "0") {

            // Get the current Media
            $media = $this->mediaRepository->getById($request->input('media_id'));
            // dd($category->previewImage);

            if ($category->previewImage && $category->previewImage->id !== (int) $request->input('media_id')) {
                // Remove media from the given category
                $category->previewImage()->delete();

                $media->update([
                    'mediatable_type'   => config('shopper.models.category'),
                    'mediatable_id'     => $category->id
                ]);
            }

            $media->update([
                'mediatable_type'   => config('shopper.models.category'),
                'mediatable_id'     => $category->id
            ]);
        }

        notify()->success(__('Category Successfully Updated'));

        return redirect()->route('shopper.categories.index');
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
            notify()->success(__('Category deleted successfully'));

            if ($request->isXmlHttpRequest()) {
                return response()->json(['redirect_url' => route('shopper.categories.index')]);
            }

            return redirect()->route('shopper.categories.index');
        } catch (\Exception $e) {
            notify()->error(__("We can't delete this category!"));

            return back();
        }
    }
}
