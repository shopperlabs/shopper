<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;

final class UpdateAvatarRequest extends FormRequest
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
            'avatar' => ['required', File::image()->max(1024)],
        ];
    }

    public function avatar(): UploadedFile
    {
        $file = $this->file('avatar');

        if (! $file instanceof UploadedFile) {
            throw ValidationException::withMessages(['avatar' => __('validation.image', ['attribute' => 'avatar'])]);
        }

        return $file;
    }
}
