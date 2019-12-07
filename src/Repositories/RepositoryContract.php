<?php

namespace Shopper\Framework\Repositories;

interface RepositoryContract
{
    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*']);

    /**
     * @return mixed
     */
    public function count();

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function createMultiple(array $data);

    /**
     * @return mixed
     */
    public function delete();

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param array $ids
     * @return mixed
     */
    public function deleteMultipleById(array $ids);

    /**
     * @param array $columns
     * @return mixed
     */
    public function first(array $columns = ['*']);

    /**
     * @param array $columns
     * @return mixed
     */
    public function get(array $columns = ['*']);

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function getById($id, array $columns = ['*']);

    /**
     * @param $item
     * @param $column
     * @param array $columns
     * @return mixed
     */
    public function getByColumn($item, $column, array $columns = ['*']);

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     * @return mixed
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null);

    /**
     * @param $id
     * @param array $data
     * @param array $options
     * @return mixed
     */
    public function updateById($id, array $data, array $options = []);

    /**
     * @param $limit
     * @return mixed
     */
    public function limit($limit);

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function orderBy($column, $value);

    /**
     * @param $column
     * @param $value
     * @param string $operator
     * @return mixed
     */
    public function where($column, $value, $operator = '=');

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function whereIn($column, $value);

    /**
     * @param $relations
     * @return mixed
     */
    public function with($relations);
}
