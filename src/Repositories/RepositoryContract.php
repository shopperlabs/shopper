<?php

declare(strict_types=1);

namespace Shopper\Framework\Repositories;

interface RepositoryContract
{
    public function all(array $columns = ['*']);

    public function count();

    public function create(array $data);

    public function createMultiple(array $data);

    public function delete();

    public function deleteById(int $id);

    public function deleteMultipleById(array $ids);

    public function first(array $columns = ['*']);

    public function get(array $columns = ['*']);

    public function getById(int $id, array $columns = ['*']);

    public function getByColumn(string $item, string $column, array $columns = ['*']);

    public function paginate(int $limit = 25, array $columns = ['*'], string $pageName = 'page', $page = null);

    public function updateById(int $id, array $data, array $options = []);

    public function limit(int $limit);

    public function orderBy(string $column, string $direction);

    public function where(string $column, mixed $value, string $operator = '=');

    public function whereIn(string $column, string|array $values);

    public function with($relations);

    public function pluck(string $column, $key = null);
}
