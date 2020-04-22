<?php

namespace Shopper\Framework\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateShopRequest extends AbstractBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Return store rules
     *
     * @return array
     */
    public function getUpdateRules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'max:100',
                Rule::unique(shopper_table('shops'), 'name')->ignore($this->route()->parameter('store'))
            ],
            'email' => [
                'sometimes',
                'required',
                'max:255',
                Rule::unique(shopper_table('shops'), 'email')->ignore($this->route()->parameter('store'))
            ],
            'phone_number' => 'sometimes|required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => __("A Store with this name already exists."),
            'name.required' => __("Store name is required."),
            'name.max' => __("A Store can reach 100 caracters."),
            'email.required' => __("Store Email is required."),
            'email.unique' => __("A Store with this Email Address already exists."),
        ];
    }
}
