<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Legal;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @mixin Legal
 */
class LegalResource extends JsonApiResource
{
    public static function filters(): array
    {
        return [
            AllowedFilter::partial('title'),
        ];
    }

    public static function sorts(): array
    {
        return ['title', 'updated_at'];
    }

    final public function toType(Request $request): string
    {
        return 'legals';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
