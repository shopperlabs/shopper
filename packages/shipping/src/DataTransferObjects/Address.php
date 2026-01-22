<?php

declare(strict_types=1);

namespace Shopper\Shipping\DataTransferObjects;

final readonly class Address
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $street,
        public string $city,
        public string $postalCode,
        public string $state,
        public string $country,
        public ?string $company = null,
        public ?string $street2 = null,
        public ?string $phone = null,
        public ?string $email = null,
    ) {}

    public function fullName(): string
    {
        return mb_trim("{$this->firstName} {$this->lastName}");
    }

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'company' => $this->company,
            'street' => $this->street,
            'street2' => $this->street2,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'state' => $this->state,
            'country' => $this->country,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}
