<?php

namespace Shopper\Framework\Http\Requests;

use Illuminate\Validation\Rule;

class InventoryRequest extends AbstractBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->isSuperAdmin();
    }

    /**
     * Return store rules
     *
     * @return array
     */
    public function getStoreRules(): array
    {
        return [
            'name' => 'required|max:255|unique:' . shopper_table('inventories'),
            'street' => 'required|max:255|unique:' . shopper_table('inventories'),
            'email' => 'required|max:255|unique:' . shopper_table('inventories'),
            'phone_number' => 'required',
            'country' => 'required',
            'city'   => 'required'
        ];
    }

    /**
     * Return update rules.
     *
     * @return array
     */
    public function getUpdateRules(): array
    {
        return [
            'name'  => 'sometimes|required',
            'email'  => [
                'sometimes',
                'required',
                Rule::unique(shopper_table('inventories'), 'email')->ignore($this->route()->parameter('inventory'))
            ],
            'street'  => [
                'sometimes',
                'required',
                Rule::unique(shopper_table('inventories'), 'street')->ignore($this->route()->parameter('inventory'))
            ],
            'country' => 'sometimes|required',
            'city' => 'sometimes|required',
            'phone_number' => 'sometimes|required',
        ];
    }
}
