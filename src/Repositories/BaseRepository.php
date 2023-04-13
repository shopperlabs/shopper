<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Exceptions\GeneralException;

abstract class BaseRepository implements RepositoryContract
{
    protected Model $model;

    protected Builder $query;

    protected ?int $take;

    protected array $with = [];

    protected array $wheres = [];

    protected array $whereIns = [];

    protected array $orderBys = [];

    protected array $scopes = [];

    /**
     * @throws GeneralException
     */
    public function __construct()
    {
        $this->makeModel();
    }

    public function __call(string $scope, array $args): self
    {
        $this->scopes[$scope] = $args;

        return $this;
    }

    abstract public function model(): string;

    /**
     * @throws GeneralException
     */
    public function makeModel(): Model
    {
        $model = resolve($this->model());

        if (! $model instanceof Model) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . Model::class);
        }

        return $this->model = $model;
    }

    public function all(array $columns = ['*']): Collection
    {
        $this->newQuery()->eagerLoad();

        $models = $this->query->get($columns);

        $this->unsetClauses();

        return $models;
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function create(array $data): Model
    {
        $this->unsetClauses();

        return $this->model->create($data);
    }

    public function createMultiple(array $data): Collection
    {
        $models = new Collection();

        foreach ($data as $d) {
            $models->push($this->create($d));
        }

        return $models;
    }

    public function delete()
    {
        $this->newQuery()->setClauses()->setScopes();

        $result = $this->query->delete();

        $this->unsetClauses();

        return $result;
    }

    public function deleteById($id): bool
    {
        $this->unsetClauses();

        return $this->getById($id)->delete();
    }

    public function deleteMultipleById(array $ids): int
    {
        return $this->model->destroy($ids);
    }

    public function first(array $columns = ['*']): Model
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->firstOrFail($columns);

        $this->unsetClauses();

        return $model;
    }

    public function get(array $columns = ['*']): Collection
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->get($columns);

        $this->unsetClauses();

        return $models;
    }

    public function getById($id, array $columns = ['*']): Collection|Model
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id, $columns);
    }

    public function getByColumn(string $item, string $column, array $columns = ['*']): ?Model
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    public function paginate(int $limit = 25, array $columns = ['*'], string $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
    }

    public function updateById(int $id, array $data, array $options = []): Collection|Model
    {
        $this->unsetClauses();

        $model = $this->getById($id);

        $model->update($data, $options);

        return $model;
    }

    public function limit(int $limit): self
    {
        $this->take = $limit;

        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    public function where(string $column, $value, string $operator = '='): self
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    public function whereIn(string $column, string|array $values): self
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    public function with($relations): self
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    public function pluck(string $column, $key = null): \Illuminate\Support\Collection
    {
        $this->newQuery();

        $results = $this->query->pluck($column, $key);

        $this->unsetClauses();

        return $results;
    }

    protected function newQuery(): self
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    protected function eagerLoad(): self
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        return $this;
    }

    protected function setClauses(): self
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (isset($this->take) && null !== $this->take) {
            $this->query->take($this->take);
        }

        return $this;
    }

    protected function setScopes(): self
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->{$method}(...$args);
        }

        return $this;
    }

    protected function unsetClauses(): self
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }
}
