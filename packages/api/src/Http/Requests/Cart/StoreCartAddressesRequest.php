<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCartAddressesRequest extends FormRequest
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
            'shipping_address' => ['required_without:billing_address', 'array'],
            'billing_address' => ['required_without:shipping_address', 'array'],
            ...$this->addressRules('shipping_address'),
            ...$this->addressRules('billing_address'),
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach (['shipping_address', 'billing_address'] as $key) {
            $code = $this->input("{$key}.country_code");

            if (is_string($code)) {
                $this->merge([
                    $key => array_merge((array) $this->input($key), [
                        'country_code' => mb_strtoupper($code),
                    ]),
                ]);
            }
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function addressRules(string $prefix): array
    {
        return [
            "{$prefix}.first_name" => ['nullable', 'string', 'max:255'],
            "{$prefix}.last_name" => ["required_with:{$prefix}", 'string', 'max:255'],
            "{$prefix}.company" => ['nullable', 'string', 'max:255'],
            "{$prefix}.address_1" => ["required_with:{$prefix}", 'string', 'max:255'],
            "{$prefix}.address_2" => ['nullable', 'string', 'max:255'],
            "{$prefix}.city" => ["required_with:{$prefix}", 'string', 'max:255'],
            "{$prefix}.state" => ['nullable', 'string', 'max:255'],
            "{$prefix}.postal_code" => ["required_with:{$prefix}", 'string', 'max:255'],
            "{$prefix}.phone" => ['nullable', 'string', 'max:255'],
            "{$prefix}.country_code" => ["required_with:{$prefix}", 'string', Rule::exists(shopper_table('countries'), 'cca2')],
        ];
    }
}
