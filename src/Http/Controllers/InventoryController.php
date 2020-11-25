<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Shopper\Framework\Models\Shop\Inventory\Inventory;

class InventoryController extends Controller
{
	/**
	 * Display Inventory Index.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('shopper::pages.settings.inventories.index');
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
	 * Display Inventory update form.
	 *
	 * @param  Inventory  $inventory
	 * @return \Illuminate\View\View
	 */
	public function edit(Inventory $inventory)
	{
		return view('shopper::pages.settings.inventories.edit', compact('inventory'));
	}
}
