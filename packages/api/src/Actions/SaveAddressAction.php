<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Contracts\Address as AddressContract;
use Shopper\Core\Models\Country;

final readonly class SaveAddressAction
{
    public function __construct(
        private DatabaseManager $database,
    ) {}

    /**
     * Create or update a customer address. A country is referenced by its
     * cca2 code on the wire, and the shipping/billing default flags stay
     * exclusive per customer.
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(Model&Authenticatable $customer, array $data, ?Address $address = null): Address
    {
        return $this->database->transaction(function () use ($customer, $data, $address): Address {
            if (isset($data['country_code'])) {
                $data['country_id'] = Country::query()
                    ->where('cca2', mb_strtoupper((string) $data['country_code']))
                    ->value('id');

                unset($data['country_code']);
            }

            $data['user_id'] = $customer->getAuthIdentifier();

            /** @var Address $address */
            $address = $address === null
                ? resolve(AddressContract::class)::query()->create($data)
                : tap($address)->update($data);

            foreach (['shipping_default', 'billing_default'] as $flag) {
                if (($data[$flag] ?? false) === true) {
                    resolve(AddressContract::class)::query()
                        ->where('user_id', $customer->getAuthIdentifier())
                        ->whereKeyNot($address->getKey())
                        ->update([$flag => false]);
                }
            }

            return $address->load('country');
        });
    }
}
