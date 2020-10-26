<?php

namespace Shopper\Framework\Models\User;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'can_be_removed' => 'boolean',
    ];

    /**
     * Get a lists of permissions groups.
     *
     * @return array
     */
    public static function groups()
    {
        return [
          'system' => __("System"),
          'brands' => __("Brands"),
          'categories' => __("Categories"),
          'collections' => __("Collections"),
          'products' => __("Products"),
          'customers' => __("Customers"),
          'orders' => __("Orders"),
          'discounts' => __("Discounts"),
        ];
    }
}
