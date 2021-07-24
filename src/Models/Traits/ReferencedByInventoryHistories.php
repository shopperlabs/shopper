<?php

namespace Shopper\Framework\Models\Traits;

use Shopper\Framework\Models\InventoryHistory;
use Illuminate\Database\Eloquent\Relations\morphMany;

trait ReferencedByInventoryHistories
{
    public function stockMutations(): morphMany
    {
        return $this->morphMany(InventoryHistory::class, 'reference');
    }
}
