<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Traits\HasSlug;

class Carrier extends Model
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('carriers');
    }
}
