<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Traits\HasSlug;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string|null $content
 * @property-read bool $is_enabled
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Legal extends Model
{
    use HasSlug;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('legals');
    }

    /**
     * @param  Builder<Legal>  $query
     * @return Builder<Legal>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }
}
