<?php

namespace Shopper\Framework\Http\Controllers;

class AttributeController extends ShopperBaseController
{
    /**
     * Browse Attribute view page.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('browse_attributes');

        return view('shopper::pages.settings.attributes.index');
    }

    /**
     * Create Attribute view page.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('add_attributes');

        return view('shopper::pages.settings.attributes.create');
    }
}
