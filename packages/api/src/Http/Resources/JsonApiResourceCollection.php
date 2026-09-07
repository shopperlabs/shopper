<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Resources\JsonApi\AnonymousResourceCollection;
use Illuminate\Http\Resources\JsonApi\JsonApiResource as BaseJsonApiResource;

final class JsonApiResourceCollection extends AnonymousResourceCollection
{
    public function with($request): array
    {
        $request = $this->resolveJsonApiRequestFrom($request);

        return JsonApiResource::compoundDocumentMembers(
            $this->collection
                ->map(fn (BaseJsonApiResource $resource) => $resource->resolveIncludedResourceObjects($request))
                ->flatten(depth: 1)
        );
    }
}
