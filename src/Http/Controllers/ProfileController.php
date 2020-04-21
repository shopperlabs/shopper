<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class ProfileController extends Controller
{
    /**
     * Display Shopper User Profile.
     *
     * @param  string  $section
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function profile($section = '')
    {
        if (empty($section)) {
            return redirect()->route('shopper.profile', 'profile');
        }

        return view('shopper::pages.users.profile', compact('section'));
    }

    public function security()
    {
        return view('shopper::pages.users.security');
    }
}
