<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Shopper\Core\Jobs\SyncCollectionProductsJob;
use Shopper\Core\Models\CollectionRule;

final class CollectionRuleObserver
{
    public function saved(CollectionRule $rule): void
    {
        $this->syncCollection($rule);
    }

    public function deleting(CollectionRule $rule): void
    {
        $this->syncCollection($rule);
    }

    private function syncCollection(CollectionRule $rule): void
    {
        if ($rule->collection->isAutomatic()) {
            SyncCollectionProductsJob::dispatch($rule->collection);
        }
    }
}
