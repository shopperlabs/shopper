<?php

declare(strict_types=1);

namespace Shopper\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopper\Core\Actions\SyncCollectionProductsAction;
use Shopper\Core\Models\Contracts\Collection;

final class SyncCollectionProductsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Collection $collection
    ) {}

    public function handle(SyncCollectionProductsAction $action): void
    {
        $action->execute($this->collection);
    }
}
