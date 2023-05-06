<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Shopper\Framework\Models\User\Role;

class SettingController extends Controller
{
    public function initialize(): View
    {
        return view('shopper::pages.settings.initialize');
    }

    public function role(Role $role): View
    {
        return view('shopper::pages.settings.management.role', compact('role'));
    }
}
