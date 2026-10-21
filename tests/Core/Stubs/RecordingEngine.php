<?php

declare(strict_types=1);

namespace Tests\Core\Stubs;

use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\NullEngine;

final class RecordingEngine extends NullEngine
{
    /** @var array<string, array<string, mixed>> */
    public array $documents = [];

    public static function register(): self
    {
        $engine = new self;

        app()->instance(EngineManager::class, resolve(EngineManager::class)->extend('recording', fn (): RecordingEngine => $engine));
        config(['scout.driver' => 'recording']);

        return $engine;
    }

    public function update($models): void
    {
        foreach ($models as $model) {
            $this->documents[$model->getScoutKey()] = $model->toSearchableArray();
        }
    }

    public function delete($models): void
    {
        foreach ($models as $model) {
            unset($this->documents[$model->getScoutKey()]);
        }
    }
}
