<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\AnonymousResourceCollection;
use Illuminate\Http\Resources\JsonApi\JsonApiResource as BaseJsonApiResource;
use Illuminate\Support\Collection;
use Shopper\Http\Support\Vary;

final class JsonApiResourceCollection extends AnonymousResourceCollection
{
    public function with($request): array
    {
        $request = $this->resolveJsonApiRequestFrom($request);

        return JsonApiResource::compoundDocumentMembers(
            $this->collection
                ->map(fn (BaseJsonApiResource $resource): Collection => $resource->resolveIncludedResourceObjects($request))
                ->flatten(depth: 1)
        );
    }

    public function withResponse(Request $request, JsonResponse $response): void
    {
        parent::withResponse($request, $response);

        if ($request->attributes->get('shopper_calculated_prices') === true) {
            Vary::add($response, 'Authorization');
        }
    }
}
