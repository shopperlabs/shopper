<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Shopper\Api\Concerns\NormalizesCartInput;
use Shopper\Cart\Models\CartLine;

final class StoreCartLineRequest extends FormRequest
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
            'purchasable_type' => ['required', 'string', Rule::in(['product', 'variant'])],
            'purchasable_id' => ['required', 'string'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:'.CartLine::MAXIMUM_QUANTITY],
            'metadata' => ['nullable', ...$this->metadataRules()],
        ];
    }
}
