<?php

declare(strict_types=1);

namespace Shopper\Core\Console;

use Illuminate\Console\Command;
use Shopper\Core\Actions\SyncCollectionProductsAction;
use Shopper\Core\Models\Contracts\Collection;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'shopper:collections:sync')]
final class SyncCollectionsCommand extends Command
{
    protected $signature = 'shopper:collections:sync
                            {--collection= : Sync a specific collection by ID}';

    protected $description = 'Sync products for automatic collections based on their rules';

    public function handle(SyncCollectionProductsAction $action): int
    {
        $collectionId = $this->option('collection');

        if ($collectionId) {
            return $this->syncSingleCollection($action, (int) $collectionId);
        }

        return $this->syncAllCollections($action);
    }

    private function syncSingleCollection(SyncCollectionProductsAction $action, int $collectionId): int
    {
        $collection = resolve(Collection::class)::query()->find($collectionId);

        if (! $collection) {
            $this->components->error("Collection with ID {$collectionId} not found.");

            return self::FAILURE;
        }

        if ($collection->isManual()) {
            $this->components->warn("Collection '{$collection->name}' is manual and cannot be synced.");

            return self::SUCCESS;
        }

        $count = $action->execute($collection);

        $this->components->info("Synced {$count} products to collection '{$collection->name}'.");

        return self::SUCCESS;
    }

    private function syncAllCollections(SyncCollectionProductsAction $action): int
    {
        $collections = resolve(Collection::class)::query()
            ->automatic()
            ->with('rules')
            ->get();

        if ($collections->isEmpty()) {
            $this->components->info('No automatic collections found.');

            return self::SUCCESS;
        }

        $this->components->info("Syncing {$collections->count()} automatic collection(s)...");

        $collections->each(function (Collection $collection) use ($action): void {
            $count = $action->execute($collection);
            $this->components->twoColumnDetail($collection->name, "{$count} products");
        });

        $this->newLine();
        $this->components->info('All automatic collections have been synced.');

        return self::SUCCESS;
    }
}
