<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property string $title
 * @property string $slug
 * @property string | null $content
 * @property bool $is_enabled
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Legal extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function getTable(): string
    {
        return shopper_table('legals');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }
}
