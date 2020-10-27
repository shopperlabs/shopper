<?php

namespace Shopper\Framework\Http\Controllers\Api\Settings;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Shopper\Framework\Models\System\Setting;

class SettingController extends Controller
{
    /**
     * Validate configuration settings fields.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function general(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shop_name'           => 'required|max:100',
            'shop_email'          => 'required|email',
            'shop_about'          => 'nullable|string',
            'shop_logo'           => 'nullable',
            'shop_country_id'     => 'required',
            'shop_currency_id'    => 'required',
            'shop_street_address' => 'required|string',
            'shop_zipcode'        => 'required|numeric',
            'shop_city'           => 'required|string',
            'shop_phone_number'   => 'nullable',
            'shop_lng'            => 'nullable|numeric',
            'shop_lat'            => 'nullable|numeric',
            'shop_facebook_link'  => 'nullable|string',
            'shop_instagram_link' => 'nullable|string',
            'shop_twitter_link'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->messages()->get('*'),
                'data'    => $request->all(),
            ], 400);
        }

        foreach ($request->except(['shop_logo', 'is_default_inventory']) as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], [
                'value'  => $value,
                'locked' => true,
                'display_name' => Setting::lockedAttributesDisplayName($key),
            ]);
        }

        if ($request->input('shop_logo') && $request->input('shop_logo') !== "null") {
            Setting::query()->create([
                'key' => 'shop_logo',
                'value'  => $request->input('shop_logo')->store('/', config('shopper.system.storage.disks.uploads')),
                'display_name' => Setting::lockedAttributesDisplayName('shop_logo'),
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => __("Store successfully created, you can now access to your dashboard to manage everything."),
        ]);
    }
}
