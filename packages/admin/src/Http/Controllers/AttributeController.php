<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers;

use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Attribute;

final class AttributeController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_attributes');

        return view('shopper::pages.attributes.index');
    }

    public function create(): View
    {
        $this->authorize('add_attributes');

        return view('shopper::pages.attributes.create');
    }

    public function edit(Attribute $attribute): View
    {
        $this->authorize('edit_attributes');

        return view('shopper::pages.attributes.edit', compact('attribute'));
    }
}
