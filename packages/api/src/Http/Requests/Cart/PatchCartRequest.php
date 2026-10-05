<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Shopper\Api\Concerns\NormalizesCartInput;

final class PatchCartRequest extends FormRequest
{
    use NormalizesCartInput;

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
            'currency_code' => ['sometimes', 'required', 'string', $this->currencyExistsRule()],
            'zone_code' => ['sometimes', 'required', 'string', Rule::exists(shopper_table('zones'), 'code')->where('is_enabled', true)],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'metadata' => ['sometimes', 'nullable', ...$this->metadataRules()],
        ];
    }
}
