<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 * @package App\Repositories
 */
abstract class Repository implements RepositoryInterface
{
    // model property on class instances
    protected $model;

    /**
     * Constructor to bind model to repo
     *
     * Repository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $fields
     * @return $this
     */
    public function selectFields(array $fields = ["*"])
    {
        $this->model = $this->model
            ->select($fields);

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function show(int $id) : object
    {
        $this->model = $this->model->findOrFail($id);

        return $this;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function get(int $id) : object
    {
        $this->model = $this->model
            ->where('id', $id)
            ->firstOrFail();

        return $this;
    }

    /**
     * @return $this
     */
    public function all() : object
    {
        $this->model = $this->model->all();

        return $this;
    }

    /**
     * @param int $paginate
     * @return $this
     */
    public function paginate(int $paginate = 15) : object
    {
        $this->model = $this->model->paginate($paginate);

        return $this;
    }

    /**
     * Create a new record in the database
     *
     * @param array $data
     * @return $this
     */
    public function create(array $data) : object
    {
        $this->model = $this->model->create($data);

        return $this;
    }

    /**
     * Update record in the database
     *
     * @param array $data
     * @return $this
     */
    public function update(array $data) : object
    {
        $this->model->update($data);

        return $this;
    }

    /**
     * Remove record from the database
     *
     * @param int $id
     * @return $this
     */
    public function delete(int $id) : object
    {
        $this->model->destroy($id);

        return $this;
    }

    /**
     * Get the associated model
     *
     * @return Model
     */
    public function getModel() : object
    {
        return $this->model;
    }

    /**
     * Set the associated model
     *
     * @param $model
     * @return $this
     */
    public function setModel($model) : object
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Eager load database relationships
     *
     * @param $relations
     * @return $this
     */
    public function with($relations) : object
    {
        $this->model->with($relations);

        return $this;
    }

    public function getWhereIn(array $values, string $column = 'id') : object
    {
        $this->model = $this->model
            ->whereIn($column, $values)
            ->get();

        return $this;
    }
}

