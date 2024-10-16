<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property bool $approved
 * @property bool $is_recommended
 * @property string | null $title
 * @property string | null $content
 * @property int $rating
 */
class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_recommended' => 'boolean',
        'approved' => 'boolean',
    ];

    public function getTable(): string
    {
        return shopper_table('reviews');
    }

    public function reviewrateable(): MorphTo
    {
        return $this->morphTo();
    }

    public function author(): MorphTo
    {
        return $this->morphTo('author');
    }

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

    public function updateRating(int $id, array $data): self
    {
        $rating = self::find($id);
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
        return self::query()->find($id)->delete();
    }

    public function updatedApproved(bool $approved = false): void
    {
        $this->approved = $approved;

        $this->save();
    }
}
