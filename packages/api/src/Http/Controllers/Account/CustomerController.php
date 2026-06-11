<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Account;

use Illuminate\Http\Request;
use Shopper\Api\Http\Requests\Account\UpdateProfileRequest;
use Shopper\Api\Http\Resources\CustomerResource;
use TiMacDonald\JsonApi\JsonApiResource;

final class CustomerController
{
    public function show(Request $request): JsonApiResource
    {
        return CustomerResource::make($request->user());
    }

    public function update(UpdateProfileRequest $request): JsonApiResource
    {
        $customer = $request->user();

        $customer->forceFill($request->validated())->save();

        return CustomerResource::make($customer);
    }
}
