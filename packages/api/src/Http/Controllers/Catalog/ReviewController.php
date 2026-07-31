<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Catalog;

use Illuminate\Database\Eloquent\Model;
use Shopper\Api\Concerns\BuildsApiQueries;
use Shopper\Api\Http\Resources\ReviewResource;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Review;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

final class ReviewController
{
    use BuildsApiQueries;

    public function index(string $slug): JsonApiResourceCollection
    {
        $product = resolve(Product::class)::query()
            ->publish()
            ->where('slug', $slug)
            ->firstOrFail();

        $query = Review::query()
            ->approved()
            ->where('reviewrateable_type', $product->getMorphClass())
            ->where('reviewrateable_id', $product->getKey())
            ->with('author');

        $reviews = $this->paginated('review', $query, defaultSort: '-created_at');

        return ReviewResource::collection($reviews)
            ->additional(['meta' => $this->aggregates($product)]);
    }

    /**
     * The most recent approved reviews across the shop, capped server-side
     * and never paginated: the shape a testimonials widget needs, without
     * exposing the full review corpus to enumeration.
     */
    public function latest(): JsonApiResourceCollection
    {
        $limit = max(1, (int) config('shopper.api.resources.review.latest_limit', 20));

        $reviews = Review::query()
            ->approved()
            ->with('author')
            ->latest()
            ->limit($limit)
            ->get();

        return ReviewResource::collection($reviews);
    }

    /**
     * Count, average and star distribution in one aggregate query over the
     * exact predicate the listing paginates, so the numbers can never
     * disagree with the rows.
     *
     * @return array<string, mixed>
     */
    private function aggregates(Model $product): array
    {
        $distribution = Review::query()
            ->approved()
            ->where('reviewrateable_type', $product->getMorphClass())
            ->where('reviewrateable_id', $product->getKey())
            ->toBase()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $count = (int) $distribution->sum();
        $weighted = $distribution->reduce(
            fn (int $carry, mixed $total, mixed $rating): int => $carry + ((int) $rating * (int) $total),
            0,
        );

        return [
            'reviews_count' => $count,
            'average_rating' => $count > 0 ? round($weighted / $count, 1) : null,
            'rating_distribution' => collect(range(1, 5))
                ->mapWithKeys(fn (int $rating): array => [$rating => (int) $distribution->get($rating, 0)])
                ->all(),
        ];
    }
}
