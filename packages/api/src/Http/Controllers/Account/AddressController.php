<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Account;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shopper\Api\Actions\SaveAddressAction;
use Shopper\Api\Http\Requests\Account\StoreAddressRequest;
use Shopper\Api\Http\Requests\Account\UpdateAddressRequest;
use Shopper\Api\Http\Resources\AddressResource;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Contracts\Address as AddressContract;
use Symfony\Component\HttpFoundation\Response;
use TiMacDonald\JsonApi\JsonApiResource;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class AddressController
{
    public function index(Request $request): JsonApiResourceCollection
    {
        return AddressResource::collection(
            $this->addressQuery($request)->with('country')->latest()->get()
        );
    }

    public function store(StoreAddressRequest $request, SaveAddressAction $action): JsonResponse
    {
        $address = $action->execute($request->user(), $request->validated());

        /** @var JsonResponse $response */
        $response = AddressResource::make($address)->toResponse($request);

        return $response->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateAddressRequest $request, SaveAddressAction $action, string $address): JsonApiResource
    {
        return AddressResource::make(
            $action->execute($request->user(), $request->validated(), $this->findAddress($request, $address))
        );
    }

    public function destroy(Request $request, string $address): Response
    {
        $this->findAddress($request, $address)->delete();

        return response()->noContent();
    }

    /**
     * Addresses are always resolved through the authenticated customer:
     * another customer's address is indistinguishable from a missing one.
     */
    private function findAddress(Request $request, string $publicId): Address
    {
        return $this->addressQuery($request)->where('public_id', $publicId)->firstOrFail();
    }

    /**
     * @return Builder<Address>
     */
    private function addressQuery(Request $request): Builder
    {
        return resolve(AddressContract::class)::query()
            ->where('user_id', $request->user()?->getAuthIdentifier());
    }
}
