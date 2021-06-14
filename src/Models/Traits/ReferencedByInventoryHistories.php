<?php

namespace Shopper\Framework\Models\Traits;

use Illuminate\Database\Eloquent\Relations\morphMany;
use Shopper\Framework\Models\InventoryHistory;

trait ReferencedByInventoryHistories
{
    public function stockMutations(): morphMany
    {
        return $this->morphMany(InventoryHistory::class, 'reference');
    }
}
