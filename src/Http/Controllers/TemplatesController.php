<?php

namespace Shopper\Framework\Http\Controllers;

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
            'skeleton' => Mailable::getTemplateSkeleton($type, $name, $skeleton)
        ]);
    }
}
