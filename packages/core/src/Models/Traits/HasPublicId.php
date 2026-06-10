<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasPublicId
{
    public static function bootHasPublicId(): void
    {
        static::creating(function (self $model): void {
            if (blank($model->getAttribute('public_id'))) {
                $model->setAttribute('public_id', (string) Str::ulid());
            }
        });

        // A replicated model must get its own identifier, never the source's.
        static::replicating(function (self $model): void {
            $model->setAttribute('public_id', null);
        });
    }

    /**
     * @param  array<string>  $columns
     */
    public static function findByPublicId(string $publicId, array $columns = ['*']): ?static
    {
        return static::query()->select($columns)
            ->where('public_id', $publicId)
            ->first();
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    #[Scope]
    protected function wherePublicId(Builder $query, string $publicId): Builder
    {
        return $query->where('public_id', $publicId);
    }
}
