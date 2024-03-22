<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Traits\HasSlug;

class Channel extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($channel): void {
            if ($channel->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });

        self::updating(function ($channel): void {
            if ($channel->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });
    }

    public function getTable(): string
    {
        return shopper_table('channels');
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(config('shopper.models.product'), 'productable', 'product_has_relations');
    }
}
