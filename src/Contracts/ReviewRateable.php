<?php

declare(strict_types=1);

namespace Shopper\Framework\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ReviewRateable
{
    public function ratings(): MorphMany;

    public function averageRating($round = null);

    public function averageCustomerServiceRating($round = null);

    public function averageQualityRating($round = null);

    public function averageFriendlyRating($round = null);

    public function averagePricingRating($round = null);

    public function countRating();

    public function countCustomerServiceRating();

    public function countQualityRating();

    public function countFriendlyRating();

    public function countPriceRating();

    public function sumRating();

    public function ratingPercent(int $max = 5): float;

    public function rating(array $data, Model $author, ?Model $parent = null);

    public function updateRating(int $id, array $data, ?Model $parent = null);

    public function getAllRatings(int $id, string $sort = 'desc');

    public function getApprovedRatings(int $id, string $sort = 'desc');

    public function getNotApprovedRatings(int $id, string $sort = 'desc');

    public function getRecentRatings(int $id, int $limit = 5, string $sort = 'desc');

    public function getRecentUserRatings(int $id, int $limit = 5, bool $approved = true, string $sort = 'desc');

    public function deleteRating(int $id);
}
