<?php

declare(strict_types=1);

namespace Shopper\Http\Support;

use Symfony\Component\HttpFoundation\Response;

final class Vary
{
    /**
     * Merge a header name into the response Vary list, keeping it a single
     * comma separated line. Symfony appends a second Vary header instead
     * when asked not to replace, a shape shared caches handle poorly.
     */
    public static function add(Response $response, string $header): void
    {
        $response->setVary(implode(', ', array_unique([...$response->getVary(), $header])));
    }
}
