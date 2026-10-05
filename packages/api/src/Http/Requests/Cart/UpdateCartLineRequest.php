<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Shopper\Api\Concerns\NormalizesCartInput;
use Shopper\Cart\Models\CartLine;

final class UpdateCartLineRequest extends FormRequest
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
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:'.CartLine::MAXIMUM_QUANTITY],
            'metadata' => ['sometimes', 'nullable', ...$this->metadataRules()],
        ];
    }
}
