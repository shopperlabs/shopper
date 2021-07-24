<?php

namespace Shopper\Framework\Http\Composers;

use Illuminate\View\View;

class GlobalComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $view->with('logged_in_user', auth()->user());
    }
}
