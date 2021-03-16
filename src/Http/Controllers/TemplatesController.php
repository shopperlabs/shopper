<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Http\Request;
use Shopper\Framework\Services\Mailable;

class TemplatesController extends ShopperBaseController
{
    /**
     * Create new template view.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $skeleton
     * @return \Illuminate\View\View
     */
    public function create(string $type, string $name, string $skeleton)
    {
        return view('shopper::pages.settings.mails.templates.create', [
            'skeleton' => Mailable::getTemplateSkeleton($type, $name, $skeleton),
            'type' => $type,
            'name' => $name,
        ]);
    }

    /**
     * Store a newly template to the resources folder.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function store(Request $request)
    {
        $request->validate([
            'template_name' => 'required',
            'template_description' => 'required',
        ]);

        return Mailable::createTemplate($request);
    }
}
