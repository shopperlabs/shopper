<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string|null $logo
 * @property string|null $description
 * @property string|null $link_url
 * @property string|null $instructions
 */
class PaymentMethod extends Model
{
    use HasFactory;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    protected $appends = [
        'logo_url',
    ];

    public function getTable(): string
    {
        return shopper_table('payment_methods');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return Storage::disk(config('shopper.core.storage.disk_name'))->url($this->logo);
        }

        return null;
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }
}
