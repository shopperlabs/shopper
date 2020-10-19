<?php

namespace Shopper\Framework\Http\Controllers\Api\Settings;

use Illuminate\Routing\Controller;
use Shopper\Framework\Http\Resources\Country as CountryResource;
use Shopper\Framework\Models\System\Country;

class CountryController extends Controller
{
    public function lists()
    {
        return CountryResource::collection(Country::query()->orderBy('name')->get());
    }
}
