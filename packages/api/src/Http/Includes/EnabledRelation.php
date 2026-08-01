<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Includes;

final class EnabledRelation extends PublicRelation
{
    protected static function scope(): string
    {
        return 'enabled';
    }
}
