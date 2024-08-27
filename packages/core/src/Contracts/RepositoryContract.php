<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read DatabaseManager $database
 */
interface RepositoryContract
{
    public function all(array $columns = ['*']): Collection;

    public function get(array $columns = ['*']): Collection;

    public function getById(int $id, array $columns = ['*']): ?object;

    public function getByColumn(string $column, mixed $item, array $columns = ['*']): ?object;

    public function create(array $attributes): object;

    public function update(int $id, array $attributes, array $options = []): void;

    public function delete(int $id): void;

    public function count(): int;

    public function with($relations);

    public function query(): Builder;
}
