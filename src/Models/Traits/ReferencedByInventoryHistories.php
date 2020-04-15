<?php

namespace Shopper\Framework\Models\Traits;

use Shopper\Framework\Models\InventoryHistory;

trait ReferencedByInventoryHistories
{
    /**
     * Relation with StockMutation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function stockMutations()
    {
        return $this->morphMany(InventoryHistory::class, 'reference');
    }
}
