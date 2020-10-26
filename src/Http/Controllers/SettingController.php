<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Shopper\Framework\Models\User\Role;

class SettingController extends Controller
{
    /**
     * Display Shop Initialization view.
     *
     * @return \Illuminate\View\View
     */
    public function initialize()
    {
        return view('shopper::pages.settings.initialize');
    }

    /**
     * Display Role Permission manage.
     *
     * @param  Role  $role
     * @return \Illuminate\View\View
     */
	public function role(Role $role)
    {
        return view('shopper::pages.settings.management.role', compact('role'));
    }
}
