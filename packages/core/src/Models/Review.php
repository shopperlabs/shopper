<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Database\Factories\ReviewFactory;
use Shopper\Core\Models\Contracts\Review as ReviewContract;

/**
 * @property-read int $id
 * @property-read bool $approved
 * @property-read bool $is_recommended
 * @property-read ?string $title
 * @property-read ?string $content
 * @property-read int $reviewrateable_id
 * @property-read string $reviewrateable_type
 * @property-read int $author_id
 * @property-read string $author_type
 * @property-read int $rating
 */
class Review extends Model implements ReviewContract
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('reviews');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function reviewrateable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function author(): MorphTo
    {
        return $this->morphTo('author');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createRating(Model $reviewrateable, array $data, Model $author): self
    {
        $rating = new self;
        $rating->fill(array_merge($data, [
            'author_id' => $author->id, // @phpstan-ignore-line
            'author_type' => $author->getMorphClass(),
        ]));

        $reviewrateable->ratings()->save($rating); // @phpstan-ignore-line

        return $rating;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateRating(int $id, array $data): self
    {
        /** @var Review $rating */
        $rating = self::query()->find($id);
        $rating->update($data);

        return $rating;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getAllRatings(int $id, string $sort = 'desc'): Collection
    {
        /** @var Collection<int, Review> $results */
        $results = $this->newQuery()
            ->select('*')
            ->where('reviewrateable_id', $id)
            ->orderBy('created_at', $sort)
            ->get();

        return $results;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getApprovedRatings(int $id, string $sort = 'desc'): Collection
    {
        /** @var Collection<int, Review> $results */
        $results = $this->newQuery()
            ->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', true)
            ->orderBy('created_at', $sort)
            ->get();

        return $results;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getNotApprovedRatings(int $id, string $sort = 'desc'): Collection
    {
        /** @var Collection<int, Review> $results */
        $results = $this->newQuery()
            ->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', false)
            ->orderBy('created_at', $sort)
            ->get();

        return $results;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getRecentRatings(int $id, int $limit = 5, string $sort = 'desc'): Collection
    {
        /** @var Collection<int, Review> $results */
        $results = $this->newQuery()
            ->select('*')
            ->where('reviewrateable_id', $id)
            ->where('approved', true)
            ->orderBy('created_at', $sort)
            ->limit($limit)
            ->get();

        return $results;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getRecentUserRatings(int $id, int $limit = 5, bool $approved = true, string $sort = 'desc'): Collection
    {
        /** @var Collection<int, Review> $results */
        $results = $this->newQuery()
            ->select('*')
            ->where('author_id', $id)
            ->where('approved', $approved)
            ->orderBy('created_at', $sort)
            ->limit($limit)
            ->get();

        return $results;
    }

    public function deleteRating(int $id): ?bool
    {
        return self::query()->find($id)->delete();
    }

    public function updatedApproved(bool $approved = false): void
    {
        $this->update(['approved' => $approved]);
    }

    protected static function newFactory(): ReviewFactory
    {
        return ReviewFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_recommended' => 'boolean',
            'approved' => 'boolean',
        ];
    }
}
