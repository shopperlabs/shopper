<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Shopper\Core\Enum\GenderType;

/**
 * Serializes the configured user model (any Authenticatable using the
 * Shopper customer columns).
 *
 * @property-read ?string $first_name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read string $picture
 * @property-read string $avatar_type
 * @property-read ?string $phone_number
 * @property-read ?GenderType $gender
 * @property-read ?CarbonInterface $birth_date
 * @property-read ?bool $opt_in
 * @property-read ?CarbonInterface $email_verified_at
 * @property-read ?CarbonInterface $created_at
 */
final class CustomerResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'customers';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'avatar' => $this->picture,
            'avatar_type' => $this->avatar_type,
            'phone_number' => $this->phone_number,
            'gender' => $this->gender?->value,
            'birth_date' => $this->birth_date?->toDateString(),
            'opt_in' => (bool) $this->opt_in,
            'email_verified' => $this->email_verified_at !== null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
