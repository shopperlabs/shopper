<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $apiConnection = config('shopper.api_connection');

        if ($apiConnection === 'jwt') {
            auth()->setDefaultDriver('api');
        }
    }
}
