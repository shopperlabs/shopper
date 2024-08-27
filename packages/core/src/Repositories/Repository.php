<?php

declare(strict_types=1);

namespace Shopper\Core\Repositories;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Contracts\RepositoryContract;
use Shopper\Core\Exceptions\ModelRepositoryException;

abstract class Repository implements RepositoryContract
{
    protected Builder $query;

    protected DatabaseManager $database;

    protected array $with = [];

    public function __construct()
    {
        $this->database = app(DatabaseManager::class);
        $this->query = $this->query();
    }

    abstract public function model(): string;

    public function query(): Builder
    {
        $model = resolve($this->model());

        if (! $model instanceof Model) {
            throw new ModelRepositoryException(
                message: "Class {$this->model()} must be an instance of " . Model::class
            );
        }

        return $model::query();
    }

    public function all(array $columns = ['*']): Collection
    {
        $this->eagerLoad();

        return $this->query->get($columns);
    }

    public function get(array $columns = ['*']): Collection
    {
        $this->eagerLoad();

        return $this->query->get($columns);
    }

    public function getById(int $id, array $columns = ['*']): ?Model
    {
        $this->eagerLoad();

        return $this->query->findOrFail($id, $columns);
    }

    public function getByColumn(string $column, mixed $item, array $columns = ['*']): ?Model
    {
        $this->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    public function count(): int
    {
        return $this->query->count();
    }

    public function create(array $attributes): Model
    {
        return $this->database->transaction(
            callback: fn () => $this->query->create(
                attributes: $attributes
            ),
            attempts: 3
        );
    }

    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }

    public function update(int $id, array $attributes, array $options = []): void
    {
        $this->database->transaction(
            callback: fn () => $this->getById($id)->update(
                attributes: $attributes,
                options: $options
            ),
            attempts: 3
        );
    }

    public function with($relations): self
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    protected function eagerLoad(): self
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }
}
