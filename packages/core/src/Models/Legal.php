<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Contracts\Legal as LegalContract;
use Shopper\Core\Models\Traits\HasSlug;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read ?string $content
 * @property-read bool $is_enabled
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class Legal extends Model implements LegalContract
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
