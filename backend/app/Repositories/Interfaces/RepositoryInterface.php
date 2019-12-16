<?php

namespace App\Repositories\Interfaces;

/**
 * Interface RepositoryInterface
 * @package App\Repositories
 */
interface RepositoryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param int $paginate
     * @return mixed
     */
    public function paginate(int $paginate);

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function update(array $data);

    /**
     * @param $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param $id
     * @return mixed
     */
    public function show(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function get(int $id);

    /**
     * @return mixed
     */
    public function getModel();

    /**
     * @param $model
     * @return mixed
     */
    public function setModel($model);

    /**
     * @param $relations
     * @return mixed
     */
    public function with($relations);
}

