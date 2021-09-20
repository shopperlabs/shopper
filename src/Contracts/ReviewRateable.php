<?php

namespace Shopper\Framework\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ReviewRateable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ratings();

    /**
     * @param $round
     *
     * @return float
     */
    public function averageRating($round = null);

    /**
     * @param $round
     */
    public function averageCustomerServiceRating($round = null);

    /**
     * @param $round
     */
    public function averageQualityRating($round = null);

    /**
     * @param $round
     */
    public function averageFriendlyRating($round = null);

    /**
     * @param $round
     */
    public function averagePricingRating($round = null);

    /**
     * @return int
     */
    public function countRating();

    public function countCustomerServiceRating();

    public function countQualityRating();

    public function countFriendlyRating();

    public function countPriceRating();

    /**
     * @return float
     */
    public function sumRating();

    /**
     * @param int $max
     *
     * @return float
     */
    public function ratingPercent($max = 5);

    /**
     * @param array $data
     */
    public function rating($data, Model $author, ?Model $parent = null);

    /**
     * @param int   $id
     * @param array $data
     */
    public function updateRating($id, $data, ?Model $parent = null);

    /**
     * @param int    $id
     * @param string $sort
     */
    public function getAllRatings($id, $sort = 'desc');

    /**
     * @param int    $id
     * @param string $sort
     */
    public function getApprovedRatings($id, $sort = 'desc');

    /**
     * @param int    $id
     * @param string $sort
     */
    public function getNotApprovedRatings($id, $sort = 'desc');

    /**
     * @param int    $id
     * @param int    $limit
     * @param string $sort
     */
    public function getRecentRatings($id, $limit = 5, $sort = 'desc');

    /**
     * @param int    $id
     * @param int    $limit
     * @param bool   $approved
     * @param string $sort
     */
    public function getRecentUserRatings($id, $limit = 5, $approved = true, $sort = 'desc');

    /**
     * @param int $id
     */
    public function deleteRating($id);
}
