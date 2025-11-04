<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Shopper\Core\Models\Review;

interface HasReviews
{
    public function ratings(): MorphMany;

    /**
     * @return Collection<int, mixed>
     */
    public function averageRating(?int $round = null): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function averageCustomerServiceRating(?int $round = null): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function averageQualityRating(?int $round = null): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function averageFriendlyRating(?int $round = null): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function averagePricingRating(?int $round = null): Collection;

    public function countRating(): mixed;

    /**
     * @return Collection<int, mixed>
     */
    public function countCustomerServiceRating(): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function countQualityRating(): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function countFriendlyRating(): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function countPriceRating(): Collection;

    /**
     * @return Collection<int, mixed>
     */
    public function sumRating(): Collection;

    public function ratingPercent(int $max = 5): float;

    /**
     * @param  array<string, mixed>  $data
     */
    public function rating(array $data, mixed $author, mixed $parent = null): Review;

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateRating(int $id, array $data, mixed $parent = null): Review;

    /**
     * @return Collection<int, Review>
     */
    public function getAllRatings(int $id, string $sort = 'desc'): Collection;

    /**
     * @return Collection<int, Review>
     */
    public function getApprovedRatings(int $id, string $sort = 'desc'): Collection;

    /**
     * @return Collection<int, Review>
     */
    public function getNotApprovedRatings(int $id, string $sort = 'desc'): Collection;

    /**
     * @return Collection<int, Review>
     */
    public function getRecentRatings(int $id, int $limit = 5, string $sort = 'desc'): Collection;

    /**
     * @return Collection<int, Review>
     */
    public function getRecentUserRatings(int $id, int $limit = 5, bool $approved = true, string $sort = 'desc'): Collection;

    public function deleteRating(int $id): ?bool;
}
