<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_recommended' => 'boolean',
        'approved'       => 'boolean',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('reviews');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewrateable()
    {
        return $this->morphTo();
    }

    /**
     * Return the author's information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->morphTo('author');
    }

    /**
     * @param  Model  $reviewrateable
     * @param  array  $data
     * @param  Model  $author
     * @return static
     */
    public function createRating(Model $reviewrateable, $data, Model $author)
    {
        $rating = new static();
        $rating->fill(array_merge($data, [
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]));

        $reviewrateable->ratings()->save($rating);

        return $rating;
    }

    /**
     * @param  int  $id
     * @param  array  $data
     * @return Model
     */
    public function updateRating($id, $data)
    {
        $rating = static::find($id);
        $rating->update($data);

        return $rating;
    }

    /**
     * @param  int  $id
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllRatings($id, $sort = 'desc')
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->orderBy('created_at', $sort)
            ->get();
    }

    /**
     * @param  int  $id
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getApprovedRatings($id, $sort = 'desc')
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', true)
            ->orderBy('created_at', $sort)
            ->get();
    }

    /**
     * @param  int  $id
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getNotApprovedRatings($id, $sort = 'desc')
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', false)
            ->orderBy('created_at', $sort)
            ->get();
    }

    /**
     * @param  int  $id
     * @param  int  $limit
     * @param  string  $sort
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRecentRatings($id, $limit = 5, $sort = 'desc')
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', true)
            ->orderBy('created_at', $sort)
            ->limit($limit)
            ->get();
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
        return $this->newQuery()->select('*')
            ->where('author_id', $id)
            ->where('approved', $approved)
            ->orderBy('created_at', $sort)
            ->limit($limit)
            ->get();
    }

    /**
     * Remove a rating from the storage.
     *
     * @param  int  $id
     * @return mixed
     * @throws \Exception
     */
    public function deleteRating($id)
    {
        return static::query()->find($id)->delete();
    }
}
