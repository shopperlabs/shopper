<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'approved' => 'boolean',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('reviews');
    }

    public function reviewrateable(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): BelongsTo
    {
        return $this->morphTo('author');
    }

    public function createRating(Model $reviewrateable, array $data, Model $author): self
    {
        $rating = new static();
        $rating->fill(array_merge($data, [
            'author_id' => $author->id,
            'author_type' => $author->getMorphClass(),
        ]));

        $reviewrateable->ratings()->save($rating);

        return $rating;
    }

    public function updateRating(int $id, array $data): self
    {
        $rating = static::find($id);
        $rating->update($data);

        return $rating;
    }

    public function getAllRatings(int $id, string $sort = 'desc'): Collection
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->orderBy('created_at', $sort)
            ->get();
    }

    public function getApprovedRatings(int $id, string $sort = 'desc'): Collection
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', true)
            ->orderBy('created_at', $sort)
            ->get();
    }

    public function getNotApprovedRatings(int $id, string $sort = 'desc'): Collection
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', false)
            ->orderBy('created_at', $sort)
            ->get();
    }

    public function getRecentRatings(int $id, int $limit = 5, string $sort = 'desc'): Collection
    {
        return $this->newQuery()->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', true)
            ->orderBy('created_at', $sort)
            ->limit($limit)
            ->get();
    }

    public function getRecentUserRatings(int $id, int $limit = 5, bool $approved = true, string $sort = 'desc'): Collection
    {
        return $this->newQuery()->select('*')
            ->where('author_id', $id)
            ->where('approved', $approved)
            ->orderBy('created_at', $sort)
            ->limit($limit)
            ->get();
    }

    public function deleteRating(int $id): ?bool
    {
        return static::query()->find($id)->delete();
    }
}
