<?php

namespace Shopper\Framework\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ShopRequest extends AbstractBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function wantsJson()
    {
        return true;
    }

    /**
     * Return store rules
     *
     * @return array
     */
    public function getStoreRules(): array
    {
        return [
            'name' => 'required|max:255|unique:' . shopper_table('shops'),
            'email' => 'required|max:255|unique:' . shopper_table('shops'),
            'phone_number' => 'required',
            'size_id'   => 'required|integer'
        ];
    }

    /**
     * Rules for updating a resource
     *
     * @var array
     */
    public $updateRules = [
        'name' => 'sometimes|required|max:255',
        'code' => 'sometimes|required|max:255',
        'size_id' => 'sometimes|required|integer'
    ];
}
