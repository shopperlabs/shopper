<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Shopper\Framework\Models\Shop\Review;

trait ReviewRateable
{
    public function ratings(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewrateable');
    }

    public function averageRating($round = null, bool $onlyApproved = false): Collection
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(rating), ' . $round . ') as averageReviewRateable')
                ->where($where)
                ->pluck('averageReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(rating) as averageReviewRateable')
            ->where($where)
            ->pluck('averageReviewRateable');
    }

    public function averageCustomerServiceRating($round = null, bool $onlyApproved = false): Collection
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(customer_service_rating), ' . $round . ') as averageCustomerServiceReviewRateable')
                ->where($where)
                ->pluck('averageCustomerServiceReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(customer_service_rating) as averageCustomerServiceReviewRateable')
            ->where($where)
            ->pluck('averageCustomerServiceReviewRateable');
    }

    public function averageQualityRating($round = null, bool $onlyApproved = false): Collection
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(quality_rating), ' . $round . ') as averageQualityReviewRateable')
                ->where($where)
                ->pluck('averageQualityReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(quality_rating) as averageQualityReviewRateable')
            ->where($where)
            ->pluck('averageQualityReviewRateable');
    }

    public function averageFriendlyRating($round = null, bool $onlyApproved = false): Collection
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(friendly_rating), ' . $round . ') as averageFriendlyReviewRateable')
                ->where($where)
                ->pluck('averageFriendlyReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(friendly_rating) as averageFriendlyReviewRateable')
            ->where($where)
            ->pluck('averageFriendlyReviewRateable');
    }

    public function averagePricingRating($round = null, bool $onlyApproved = false): Collection
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(pricing_rating), ' . $round . ') as averagePricingReviewRateable')
                ->where($where)
                ->pluck('averagePricingReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(pricing_rating) as averagePricingReviewRateable')
            ->where($where)
            ->pluck('averagePricingReviewRateable');
    }

    public function countRating()
    {
        return $this->ratings()
            ->selectRaw('count(rating) as countReviewRateable')
            ->pluck('countReviewRateable')->first();
    }

    public function countCustomerServiceRating(bool $onlyApproved = false): Collection
    {
        return $this->ratings()
            ->selectRaw('count(customer_service_rating) as countCustomerServiceReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countCustomerServiceReviewRateable');
    }

    public function countQualityRating(bool $onlyApproved = false): Collection
    {
        return $this->ratings()
            ->selectRaw('count(quality_rating) as countQualityReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countQualityReviewRateable');
    }

    public function countFriendlyRating(bool $onlyApproved = false): Collection
    {
        return $this->ratings()
            ->selectRaw('count(friendly_rating) as countFriendlyReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countFriendlyReviewRateable');
    }

    public function countPriceRating(bool $onlyApproved = false): Collection
    {
        return $this->ratings()
            ->selectRaw('count(price_rating) as countPriceReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countPriceReviewRateable');
    }

    public function sumRating(bool $onlyApproved = false): Collection
    {
        return $this->ratings()
            ->selectRaw('SUM(rating) as sumReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('sumReviewRateable');
    }

    public function ratingPercent(int $max = 5): float
    {
        $ratings = $this->ratings();
        $quantity = $ratings->count();
        $total = $ratings->selectRaw('Count(rating) as total')->where('rating', $max)->pluck('total')->first();

        return round($quantity * $max > 0 ? ((int) $total * 100) / $quantity : 0, 1);
    }

    public function rating(array $data, Model $author, ?Model $parent = null): Review
    {
        return (new Review())->createRating($this, $data, $author);
    }

    public function updateRating($id, $data, ?Model $parent = null): Review
    {
        return (new Review())->updateRating($id, $data);
    }

    public function getAllRatings(int $id, string $sort = 'desc'): Collection
    {
        return (new Review())->getAllRatings($id, $sort);
    }

    public function getApprovedRatings(int $id, string $sort = 'desc'): Collection
    {
        return (new Review())->getApprovedRatings($id, $sort);
    }

    public function getNotApprovedRatings(int $id, string $sort = 'desc'): Collection
    {
        return (new Review())->getNotApprovedRatings($id, $sort);
    }

    public function getRecentRatings(int $id, int $limit = 5, string $sort = 'desc'): Collection
    {
        return (new Review())->getRecentRatings($id, $limit, $sort);
    }

    public function getRecentUserRatings(int $id, int $limit = 5, bool $approved = true, string $sort = 'desc'): Collection
    {
        return (new Review())->getRecentUserRatings($id, $limit, $approved, $sort);
    }

    public function deleteRating($id): ?bool
    {
        return (new Review())->deleteRating($id);
    }
}
