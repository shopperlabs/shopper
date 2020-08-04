<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Requests\InventoryRequest;
use Shopper\Framework\Repositories\InventoryRepository;

class InventoryController extends Controller
{
	/** @var InventoryRepository */
	protected $repository;

	public function __construct(InventoryRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Display Inventory Index.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$inventories = $this->repository->get();

		return view('shopper::pages.settings.inventories.index', compact('inventories'));
	}

	/**
	 * Display Inventory Create form view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('shopper::pages.settings.inventories.create');
	}

	/**
	 * Create a new storage on the database.
	 *
	 * @param InventoryRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(InventoryRequest $request)
	{
		$this->repository->create([
			'name' => $name = $request->input('name'),
			'code' => str_slug($name),
			'street' => $request->input('street'),
			'email' => $request->input('email'),
			'country' => $request->input('country'),
			'city' => $request->input('city'),
			'postcode' => $request->input('postcode'),
			'phone_number' => $request->input('phone_number'),
		]);

		notify()->success(__('Inventory Successfully Added.'));

		return redirect()->route('shopper.settings.inventories.index');
	}

	/**
	 * Display Inventory update form.
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function edit(int $id)
	{
		$inventory = $this->repository->getById($id);

		return view('shopper::pages.settings.inventories.edit', compact('inventory'));
	}

	/**
	 * Update inventory on the storage.
	 *
	 * @param  InventoryRequest  $request
	 * @param  integer  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(InventoryRequest $request, int $id)
	{
	    if ($request->input('is_default') === 'true') {
	        foreach ($this->repository->where('is_default', true)->get() as $inventory) {
	            $inventory->update(['is_default' => false]);
            }
        }

		$this->repository->updateById($id, [
			'name' => $request->input('name'),
			'street' => $request->input('street'),
			'email' => $request->input('email'),
			'country' => $request->input('country'),
			'city' => $request->input('city'),
			'postcode' => $request->input('postcode'),
			'phone_number' => $request->input('phone_number'),
		]);

        if ($request->input('is_default') === 'true') {
            $this->repository->updateById($id, [
                'is_default' => $request->input('is_default') === 'true',
            ]);
        }

		notify()->success(__('Inventory Successfully Updated.'));

		return redirect()->route('shopper.settings.inventories.index');
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
			notify()->success(__('Inventory deleted successfully'));

			if ($request->isXmlHttpRequest()) {
				return response()->json(['redirect_url' => route('shopper.settings.inventories.index')]);
			}

			return redirect()->route('shopper.settings.inventories.index');
		} catch (\Exception $e) {
			notify()->error(__("We can't delete this inventory!"));

			return back();
		}
	}
}
