<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Account;

use Illuminate\Http\Request;
use Shopper\Api\Actions\RemoveProfilePhotoAction;
use Shopper\Api\Actions\UpdateProfilePhotoAction;
use Shopper\Api\Http\Requests\Account\UpdateAvatarRequest;
use Shopper\Api\Http\Resources\CustomerResource;
use TiMacDonald\JsonApi\JsonApiResource;

final class AvatarController
{
    public function store(UpdateAvatarRequest $request, UpdateProfilePhotoAction $action): JsonApiResource
    {
        $customer = $request->user();

        $action->execute($customer, $request->avatar());

        return CustomerResource::make($customer->refresh());
    }

    public function destroy(Request $request, RemoveProfilePhotoAction $action): JsonApiResource
    {
        $customer = $request->user();

        $action->execute($customer);

        return CustomerResource::make($customer->refresh());
    }
}
