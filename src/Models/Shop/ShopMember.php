<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ShopMember extends Model
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return shopper_table('shop_members');
    }
}
