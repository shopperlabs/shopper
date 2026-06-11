<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Shopper\Core\Enum\AddressType;

final class UpdateAddressRequest extends FormRequest
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
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'street_address' => ['sometimes', 'string', 'max:255'],
            'street_address_plus' => ['sometimes', 'nullable', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'string', 'max:255'],
            'city' => ['sometimes', 'string', 'max:255'],
            'state' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country_code' => ['sometimes', 'string', Rule::exists(shopper_table('countries'), 'cca2')],
            'type' => ['sometimes', Rule::enum(AddressType::class)],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'shipping_default' => ['sometimes', 'boolean'],
            'billing_default' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('country_code')) {
            $this->merge(['country_code' => mb_strtoupper((string) $this->string('country_code'))]);
        }
    }
}
