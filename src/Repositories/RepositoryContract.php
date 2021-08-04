<?php

namespace Shopper\Framework\Repositories;

interface RepositoryContract
{
    public function all(array $columns = ['*']);

    public function count();

    public function create(array $data);

    public function createMultiple(array $data);

    public function delete();

    /**
     * @param $id
     */
    public function deleteById($id);

    public function deleteMultipleById(array $ids);

    public function first(array $columns = ['*']);

    public function get(array $columns = ['*']);

    /**
     * @param $id
     */
    public function getById($id, array $columns = ['*']);

    /**
     * @param $item
     * @param $column
     */
    public function getByColumn($item, $column, array $columns = ['*']);

    /**
     * @param int    $limit
     * @param string $pageName
     * @param null   $page
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null);

    /**
     * @param $id
     */
    public function updateById($id, array $data, array $options = []);

    /**
     * @param $limit
     */
    public function limit($limit);

    /**
     * @param $column
     * @param $value
     */
    public function orderBy($column, $value);

    /**
     * @param $column
     * @param $value
     * @param string $operator
     */
    public function where($column, $value, $operator = '=');

    /**
     * @param $column
     * @param $value
     */
    public function whereIn($column, $value);

    /**
     * @param  $relations
     */
    public function with($relations);

    /**
     * @param  $column
     * @param null $key
     */
    public function pluck($column, $key = null);
}
