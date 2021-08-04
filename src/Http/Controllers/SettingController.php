<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;
use Shopper\Framework\Models\User\Role;

class SettingController extends Controller
{
    public function initialize()
    {
        return view('shopper::pages.settings.initialize');
    }

    public function role(Role $role)
    {
        return view('shopper::pages.settings.management.role', compact('role'));
    }
}
