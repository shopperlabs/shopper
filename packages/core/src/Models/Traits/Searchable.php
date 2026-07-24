<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Laravel\Scout\Searchable as ScoutSearchable;
use Shopper\Core\Search\ScoutIndexer;

trait Searchable
{
    use ScoutSearchable;

    public function searchableAs(): string
    {
        return $this->indexer()->searchableAs($this);
    }

    public function shouldBeSearchable(): bool
    {
        return $this->indexer()->shouldBeSearchable($this);
    }

    public function getScoutKey(): mixed
    {
        return $this->indexer()->getScoutKey($this);
    }

    public function getScoutKeyName(): string
    {
        return $this->indexer()->getScoutKeyName($this);
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return $this->indexer()->toSearchableArray($this);
    }

    public function searchableUsing(): Engine
    {
        $engines = (array) config('shopper.search.engine_map', []);

        $engine = $engines[static::class] ?? $engines[self::class] ?? null;

        return app(EngineManager::class)->engine($engine);
    }

    public function indexer(): ScoutIndexer
    {
        $indexers = (array) config('shopper.search.indexers', []);

        return app($indexers[static::class] ?? $indexers[self::class] ?? ScoutIndexer::class);
    }

    /**
     * @param  Builder<covariant static>  $query
     * @return Builder<covariant static>
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $this->indexer()->makeAllSearchableUsing($query);
    }
}
