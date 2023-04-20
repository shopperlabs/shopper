<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Contracts\View\View;
use Shopper\Framework\Repositories\DiscountRepository;

class DiscountController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_discounts');

        return view('shopper::pages.discounts.index');
    }

    public function create(): View
    {
        $this->authorize('add_discounts');

        return view('shopper::pages.discounts.create');
    }

    public function edit(int $id): View
    {
        $this->authorize('edit_discounts');

        return view('shopper::pages.discounts.edit', [
            'discount' => (new DiscountRepository())->getById($id),
        ]);
    }
}
