<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Shopper\Framework\Services\Mailable;

class TemplatesController extends ShopperBaseController
{
    public function create(string $type, string $name, string $skeleton): View
    {
        return view('shopper::pages.settings.mails.templates.create', [
            'skeleton' => Mailable::getTemplateSkeleton($type, $name, $skeleton),
            'type' => $type,
            'name' => $name,
        ]);
    }

    public function store(Request $request): ?RedirectResponse
    {
        $request->validate([
            'template_name' => 'required',
            'template_description' => 'required',
        ]);

        return Mailable::createTemplate($request);
    }
}
