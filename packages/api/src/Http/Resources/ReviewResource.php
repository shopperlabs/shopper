<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Review;

/**
 * @mixin Review
 */
final class ReviewResource extends JsonApiResource
{
    public function toType(Request $request): string
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
            'author_name' => $this->authorName(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }

    /**
     * The reviewer reduced to a display name (first name and last initial).
     * The author id, type, full surname or avatar never reach the public
     * payload: published together they would let anyone rebuild a customer's
     * purchase history.
     */
    private function authorName(): ?string
    {
        $author = $this->author;

        if ($author === null) {
            return null;
        }

        $first = (string) $author->getAttribute('first_name');
        $lastInitial = mb_substr((string) $author->getAttribute('last_name'), 0, 1);

        $name = mb_trim($first.' '.($lastInitial !== '' ? $lastInitial.'.' : ''));

        return $name !== '' ? $name : null;
    }
}
