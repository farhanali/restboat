<?php namespace Restboat\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Restboat\Models\BaseModel;

/**
 * An abstract repository with default implementations for the standard methods
 * to be expected of any repository.
 */
abstract class BaseRepository {

    /**
     * An instance of the eloquent model being handled by the repository
     *
     * @var  \Restboat\Models\BaseModel
     */
    protected $model;

    /**
     * The amount of items to show per page.
     *
     * @var integer
     */
    protected $pageSize;

    /**
     * Create the repository by injecting an eloquent model
     *
     * @param \Restboat\Models\BaseModel $model
     */
    public function __construct(BaseModel $model = NULL)
    {
        $this->model    = $model;

        $this->setPageSize(10);
    }

    /**
     * Set the page size to be used by subsequent method calls on this object.
     *
     * @param integer $pageSize
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Return the current page size in effect on this object.
     *
     * @return integer
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return \Restboat\Models\BaseModel
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update the model in the data store.
     *
     * @param  mixed $id
     * @param  array $attributes
     * @return bool
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException If model with the given id not found.
     */
    public function update($id, array $attributes)
    {
        return $this->find($id)->update($attributes);
    }

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $matches
     * @param  array  $values
     * @return static
     */
    public function updateOrCreate($matches, $values)
    {
        return $this->model->updateOrCreate($matches, $values);
    }

    /**
     * Delete the model from the data store.
     *
     * @param  mixed $id
     * @return bool|null
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException If model with the given id not found.
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Delete entries by where clause.
     *
     * @param $field
     * @param $compare
     * @param $value
     * @return mixed
     */
    public function deleteWhere($field, $compare, $value)
    {
        return $this->model->where($field, $compare, $value)->delete();
    }

    /**
     * Get all of the models from the data store.
     *
     * @param  array  $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->all($columns);
    }

    /**
     * Get all of models page by page
     *
     * @return mixed
     */
    public function paginate($columns = array('*'))
    {
        return $this->model->paginate($this->getPageSize(), $columns);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return mixed
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException If model with the given id not found.
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Get total count of entries, optionally with date.
     *
     * @param  date $date
     * @return integer
     */
    public function count($date = null)
    {
        if ($date)
        {
            return $this->model
                ->whereRaw('DATE(updated_at) = "' . $date->toDateString() . '"')
                ->count();
        }

        return $this->model->count();
    }

    /**
     * Check whether there is some entries
     *
     * @return bool
     */
    public function hasEntries()
    {
        return $this->model->count() > 0;
    }

}
