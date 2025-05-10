<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait ModelHandler
{
    /**
     * Get model instance based on model name or class
     *
     * @return Model|null
     */
    protected function resolveModel()
    {
        if ($this->model instanceof Model) {
            return $this->model;
        }

        if (is_string($this->model)) {
            $modelClass = 'App\\Models\\' . ucfirst($this->model);
            if (class_exists($modelClass)) {
                return new $modelClass();
            }
        }

        return null;
    }

    /**
     * Get paginated or all data from model
     *
     * @param int $perPage
     * @return mixed
     */
    protected function getModelData($perPage = 15)
    {
        $model = $this->resolveModel();

        if (!$model) {
            throw new \RuntimeException('Model not found or invalid');
        }

        $query = $model->query();

        // Si el modelo es News, cargamos la relación con el autor
        if ($this->model === 'news') {
            $query->with('author');
        }

        return $this->isPaginated
            ? $query->paginate($perPage)
            : $query->get();
    }

    /**
     * Find model by ID
     *
     * @param int $id
     * @return Model
     */
    protected function findModel($id)
    {
        $model = $this->resolveModel();

        if (!$model) {
            throw new \RuntimeException('Model not found or invalid');
        }

        return $model->findOrFail($id);
    }
}
