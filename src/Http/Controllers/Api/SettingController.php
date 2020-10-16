<?php

namespace Shopper\Framework\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

use Shopper\Framework\Models\System\Setting;

class SettingController extends Controller
{
    /**
     * Undocumented function
     *
     * @param [type] $id
     * @return void
     */
    public function steps($id)
    {
        $inputs = request()->all();

        switch ($id) {
            case 1:
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
        
        
                foreach ($inputs as $key => $value) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                }
                break;
            default:
                break;
        }

        return response()->json([
            'status'  => 'success',
            'data' => $inputs,
        ]);
    }
}