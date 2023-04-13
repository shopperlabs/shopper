<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Traits\HasSlug;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property bool $is_enabled
 */
class Legal extends Model
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string>
     */
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
