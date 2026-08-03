<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Review;

/**
 * @mixin Review
 */
class ReviewResource extends JsonApiResource
{
    final public function toType(Request $request): string
    {
        return 'reviews';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'rating' => $this->rating,
            'is_recommended' => $this->is_recommended,
            'author' => $this->authorPayload(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }

    /**
     * The reviewer reduced to a display name (first name and last initial)
     * and their picture, uploaded file or generated fallback alike. The
     * author id, type and full surname never reach the public payload:
     * published together they would let anyone rebuild a customer's
     * purchase history.
     *
     * @return array{name: ?string, avatar: ?string}|null
     */
    private function authorPayload(): ?array
    {
        $author = $this->author;

        if ($author === null) {
            return null;
        }

        $first = (string) $author->getAttribute('first_name');
        $lastInitial = mb_substr((string) $author->getAttribute('last_name'), 0, 1);
        $name = mb_trim($first.' '.($lastInitial !== '' ? $lastInitial.'.' : ''));

        return [
            'name' => $name !== '' ? $name : null,
            'avatar' => $author->getAttribute('picture'),
        ];
    }
}
