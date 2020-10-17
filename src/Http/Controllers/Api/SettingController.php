<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Shopper\Framework\Models\System\Setting;

use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Models\System\Currency;

class SettingController extends Controller
{
    /**
     * Validate configuration settings fields.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function configure()
    {
        $inputs = request()->all();

        $validator = Validator::make($inputs, [
            'shop_name'           => 'required|max:255',
            'shop_email'          => 'required|email',
            'shop_about'          => 'nullable|string',
            'shop_country_id'     => 'required|numeric',
            'shop_currency_id'    => 'required|numeric',
            'shop_street_address' => 'required|string',
            'shop_zipcode'        => 'required|numeric',
            'shop_city'           => 'required|string',
            'shop_phone_number'   => 'nullable',
            'shop_lng'            => 'nullable|numeric',
            'shop_lat'            => 'nullable|numeric',
            'shop_facebook_link'  => 'nullable|string',
            'shop_twitter_link'   => 'nullable|string',
            'shop_instagram_link' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->messages()->get('*'),
                'data'    => request()->all(),
            ], 400);
        }

        $country  = Country::find($inputs["shop_country_id"]);
        Setting::updateOrCreate(
            [ 'key' => 'shop_country' ], 
            [ 'display_name' => 'Shop Country', 'value' => $country ],
        );
        
        $currency = Currency::find($inputs["shop_currency_id"]);
        Setting::updateOrCreate(
            [ 'key' => 'shop_currency' ], 
            [ 'display_name' => 'Shop Currency', 'value' => $currency ],
        );

        foreach (request()->except([ 'shop_country_id', 'shop_currency_id' ]) as $key => $value) {
            $configKey = str_replace("shop_", "", $key);
            config()->set('shopper.shop.' . $configKey, $value);
        }

        return response()->json([
            'status'  => 'success',
            'data' => $inputs,
        ]);
    }
}
