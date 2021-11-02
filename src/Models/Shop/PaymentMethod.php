<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Models\Traits\HasSlug;

class PaymentMethod extends Model
{
    use HasFactory, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'logo',
        'link_url',
        'description',
        'instructions',
        'is_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['logo_url'];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('payment_methods');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return Storage::disk(config('shopper.system.storage.disks.uploads'))->url($this->logo);
        }

        return null;
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }
}
