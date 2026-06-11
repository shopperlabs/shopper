<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Shopper\Core\Enum\AddressType;

final class StoreAddressRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'street_address_plus' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country_code' => ['required', 'string', Rule::exists(shopper_table('countries'), 'cca2')],
            'type' => ['required', Rule::enum(AddressType::class)],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'shipping_default' => ['nullable', 'boolean'],
            'billing_default' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('country_code')) {
            $this->merge(['country_code' => mb_strtoupper((string) $this->string('country_code'))]);
        }
    }
}
