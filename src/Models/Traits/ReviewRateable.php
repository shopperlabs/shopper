<?php

namespace Shopper\Framework\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Shop\Review;

trait ReviewRateable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings()
    {
        return $this->morphMany(Review::class, 'reviewrateable');
    }

    /**
     * @param  null  $round
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function averageRating($round = null, $onlyApproved = false)
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(rating), '.$round.') as averageReviewRateable')
                ->where($where)
                ->pluck('averageReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(rating) as averageReviewRateable')
            ->where($where)
            ->pluck('averageReviewRateable');
    }

    /**
     * @param  null  $round
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function averageCustomerServiceRating($round = null, $onlyApproved = false)
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(customer_service_rating), '. $round .') as averageCustomerServiceReviewRateable')
                ->where($where)
                ->pluck('averageCustomerServiceReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(customer_service_rating) as averageCustomerServiceReviewRateable')
            ->where($where)
            ->pluck('averageCustomerServiceReviewRateable');
    }

    /**
     * @param  null  $round
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function averageQualityRating($round = null, $onlyApproved = false)
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(quality_rating), '. $round .') as averageQualityReviewRateable')
                ->where($where)
                ->pluck('averageQualityReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(quality_rating) as averageQualityReviewRateable')
            ->where($where)
            ->pluck('averageQualityReviewRateable');
    }

    /**
     * @param  null  $round
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function averageFriendlyRating($round = null, $onlyApproved = false)
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(friendly_rating), '. $round .') as averageFriendlyReviewRateable')
                ->where($where)
                ->pluck('averageFriendlyReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(friendly_rating) as averageFriendlyReviewRateable')
            ->where($where)
            ->pluck('averageFriendlyReviewRateable');
    }

    /**
     * @param  null  $round
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function averagePricingRating($round = null, $onlyApproved = false)
    {
        $where = $onlyApproved ? [['approved', '1']] : [];

        if ($round) {
            return $this->ratings()
                ->selectRaw('ROUND(AVG(pricing_rating), '. $round .') as averagePricingReviewRateable')
                ->where($where)
                ->pluck('averagePricingReviewRateable');
        }

        return $this->ratings()
            ->selectRaw('AVG(pricing_rating) as averagePricingReviewRateable')
            ->where($where)
            ->pluck('averagePricingReviewRateable');
    }

    /**
     * @return mixed
     */
    public function countRating()
    {
        return $this->ratings()
            ->selectRaw('count(rating) as countReviewRateable')
            ->pluck('countReviewRateable')->first();
    }

    /**
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function countCustomerServiceRating($onlyApproved = false)
    {
        return $this->ratings()
            ->selectRaw('count(customer_service_rating) as countCustomerServiceReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countCustomerServiceReviewRateable');
    }

    /**
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function countQualityRating($onlyApproved = false)
    {
        return $this->ratings()
            ->selectRaw('count(quality_rating) as countQualityReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countQualityReviewRateable');
    }

    /**
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function countFriendlyRating($onlyApproved = false) {
        return $this->ratings()
            ->selectRaw('count(friendly_rating) as countFriendlyReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countFriendlyReviewRateable');
    }

    /**
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function countPriceRating($onlyApproved = false) {
        return $this->ratings()
            ->selectRaw('count(price_rating) as countPriceReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('countPriceReviewRateable');
    }

    /**
     * @param  bool  $onlyApproved
     * @return \Illuminate\Support\Collection
     */
    public function sumRating($onlyApproved = false)
    {
        return $this->ratings()
            ->selectRaw('SUM(rating) as sumReviewRateable')
            ->where($onlyApproved ? [['approved', '1']] : [])
            ->pluck('sumReviewRateable');
    }

    /**
     * @param  int  $max
     * @return float|int
     */
    public function ratingPercent($max = 5)
    {
        $ratings = $this->ratings();
        $quantity = $ratings->count();
        $total = $ratings->selectRaw('SUM(rating) as total')->pluck('total');

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    /**
     * @param  array  $data
     * @param  Model  $author
     * @param  Model|null  $parent
     * @return Review
     */
    public function rating($data, Model $author, Model $parent = null)
    {
        return (new Review())->createRating($this, $data, $author);
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @param  Model|null  $parent
     * @return Model
     */
    public function updateRating($id, $data, Model $parent = null)
    {
        return (new Review())->updateRating($id, $data);
    }

    /**
     * @param $id
     * @param string $sort
     * @return mixed
     */
    public function getAllRatings($id, $sort = 'desc')
    {
        return (new Review())->getAllRatings($id, $sort);
    }

    /**
     * @param  int  $id
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getApprovedRatings($id, $sort = 'desc')
    {
        return (new Review())->getApprovedRatings($id, $sort);
    }

    /**
     * @param  int  $id
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getNotApprovedRatings($id, $sort = 'desc')
    {
        return (new Review())->getNotApprovedRatings($id, $sort);
    }

    /**
     * @param  int  $id
     * @param  int  $limit
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRecentRatings($id, $limit = 5, $sort = 'desc')
    {
        return (new Review())->getRecentRatings($id, $limit,  $sort);
    }

    /**
     * @param  int  $id
     * @param  int  $limit
     * @param  bool  $approved
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRecentUserRatings($id, $limit = 5, $approved = true, $sort = 'desc')
    {
        return (new Review())->getRecentUserRatings($id, $limit, $approved, $sort);
    }

    /**
     * @param  int  $id
     * @return mixed
     */
    public function deleteRating($id)
    {
        return (new Review())->deleteRating($id);
    }
}
