<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read DatabaseManager $database
 */
interface RepositoryContract
{
    /**
     * @param  array<array-key, mixed>  $columns
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * @param  array<array-key, mixed>  $columns
     */
    public function get(array $columns = ['*']): Collection;

    /**
     * @param  array<array-key, mixed>  $columns
     */
    public function getById(int $id, array $columns = ['*']): ?Model;

    /**
     * @param  array<array-key, mixed>  $columns
     */
    public function getByColumn(string $column, mixed $item, array $columns = ['*']): ?Model;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): object;

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>  $options
     */
    public function update(int $id, array $attributes, array $options = []): void;

    public function delete(int $id): void;

    public function count(): int;

    /**
     * @param  array<array-key, mixed>|string  $relations
     */
    public function with(array|string $relations);

    public function query(): Builder;
}
