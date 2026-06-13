<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SetPaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'payment_method_id' => [
                'required',
                'string',
                Rule::exists(shopper_table('payment_methods'), 'public_id')->where('is_enabled', true),
            ],
        ];
    }
}
