<?php

namespace Shopper\Framework\Http\Controllers\Api\Settings;

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
    public function general()
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
            [ 'display_name' => 'Shop Country', 'value' => $country, 'locked' => true ],
        );
        
        $currency = Currency::find($inputs["shop_currency_id"]);
        Setting::updateOrCreate(
            [ 'key' => 'shop_currency' ], 
            [ 'display_name' => 'Shop Currency', 'value' => $currency, 'locked' => true ],
        );

        foreach (request()->except(['shop_country_id', 'shop_currency_id']) as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], [
                'value'  => $value,
                'locked' => true,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'data' => $inputs,
        ]);
    }

    public function analytics()
    {
        $inputs = request()->all();

        $validator = Validator::make($inputs, [
            'google_analytics_tracking_id'  => 'nullable|string',
            'google_analytics_view_id'      => 'nullable|numeric',
            'google_analytics_add_js'       => 'nullable|string',
            'google_tag_manager_account_id' => 'nullable|string',
            'facebook_pixel_account_id'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->messages()->get('*'),
                'data'    => request()->all(),
            ], 400);
        }
        
        setEnvironmentValue($inputs);

        return response()->json([
            'status'  => 'success',
            'data' => $inputs,
        ]);
    }
}