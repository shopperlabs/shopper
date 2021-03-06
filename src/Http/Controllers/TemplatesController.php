<?php

namespace Shopper\Framework\Http\Controllers;

class TemplatesController extends ShopperBaseController
{
    /**
     * Create new template view.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $skeleton
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(string $type, string $name, string $skeleton)
    {
        return view('shopper::pages.settings.mails.templates.create',
            compact('type', 'name', 'skeleton')
        );
    }
}
