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
     *
     * @param  $round
     * @return double
     */
    public function averageRating($round = null);

    /**
     *
     * @param  $round
     * @return mixed
     */
    public function averageCustomerServiceRating($round = null);

    /**
     *
     * @param  $round
     * @return mixed
     */
    public function averageQualityRating($round = null);

    /**
     *
     * @param  $round
     * @return mixed
     */
    public function averageFriendlyRating($round = null);

    /**
     *
     * @param  $round
     * @return mixed
     */
    public function averagePricingRating($round = null);

    /**
     * @return int
     */
    public function countRating();

    /**
     * @return mixed
     */
    public function countCustomerServiceRating();

    /**
     * @return mixed
     */
    public function countQualityRating();

    /**
     * @return mixed
     */
    public function countFriendlyRating();

    /**
     * @return mixed
     */
    public function countPriceRating();

    /**
     * @return double
     */
    public function sumRating();

    /**
     *
     * @param  int  $max
     * @return double
     */
    public function ratingPercent($max = 5);

    /**
     *
     * @param  array  $data
     * @param  Model  $author
     * @param  Model  $parent
     * @return mixed
     */
    public function rating($data, Model $author, Model $parent = null);

    /**
     *
     * @param  int  $id
     * @param  array  $data
     * @param  Model  $parent
     * @return mixed
     */
    public function updateRating($id, $data, Model $parent = null);

    /**
     *
     * @param  int  $id
     * @param  string  $sort
     * @return mixed
     */
    public function getAllRatings($id, $sort = 'desc');

    /**
     *
     * @param  int  $id
     * @param  string  $sort
     * @return mixed
     */
    public function getApprovedRatings($id, $sort = 'desc');

    /**
     *
     * @param  int  $id
     * @param  string  $sort
     * @return mixed
     */
    public function getNotApprovedRatings($id, $sort = 'desc');

    /**
     * @param  int  $id
     * @param  int  $limit
     * @param  string  $sort
     * @return mixed
     */
    public function getRecentRatings($id, $limit = 5, $sort = 'desc');

    /**
     * @param  int  $id
     * @param  int  $limit
     * @param  bool  $approved
     * @param  string  $sort
     * @return mixed
     */
    public function getRecentUserRatings($id, $limit = 5, $approved = true, $sort = 'desc');

    /**
     *
     * @param  int  $id
     * @return mixed
     */
    public function deleteRating($id);
}
