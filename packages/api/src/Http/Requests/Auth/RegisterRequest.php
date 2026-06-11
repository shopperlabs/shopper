<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

final class RegisterRequest extends FormRequest
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
        /** @var class-string<Model> $model */
        $model = (string) config('auth.providers.users.model');

        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique((new $model)->getTable(), 'email')],
            'password' => ['required', 'string', Password::defaults()],
            'opt_in' => ['nullable', 'boolean'],
        ];
    }
}
