<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use InvalidArgumentException;

trait ModelHandler
{
    /**
     * Get model instance based on model name or class
     *
     * @return Model|null
     */
    protected function resolveModel($module = null)
    {
        if ($this->model instanceof Model) {
            return $this->model;
        }

        if (is_string($this->model)) {
            $classCandidates = [];

            if ($module) {
                $classCandidates[] = "App\\Modules\\$module\\Model\\" . ucfirst($this->model);
            }

            $classCandidates[] = "App\\Modules\\" . ucfirst($this->model) . "\\Model\\" . ucfirst($this->model);

            // Klasik App\Models fallback
            $classCandidates[] = "App\\Models\\" . ucfirst($this->model);

            foreach ($classCandidates as $modelClass) {
                if (class_exists($modelClass)) {
                    return new $modelClass();
                }
            }

            // Hata durumunda logla
            throw new InvalidArgumentException("Model class could not be resolved for: {$this->model}");
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
            throw new InvalidArgumentException('Model not found or invalid');
        }

        // Verificar si el modelo usa el trait Cacheable
        if (in_array('App\Traits\Cacheable', class_uses_recursive($model))) {
            return $this->isPaginated
            ? $model->getCachedList(null, $this->perPage ?? $perPage)
            : $model->getCachedList();
        }

        $query = $model->query();

        if ($this->model === 'news') {
            $query->with('author');
        }

        return $this->isPaginated
            ? $query->paginate($perPage)
            : $query->get();
    }

    protected function findModel($id)
    {
        $model = $this->resolveModel();

        if (!$model) {
            throw new InvalidArgumentException('Model not found or invalid');
        }

        // Verificar si el modelo usa el trait Cacheable
        if (in_array('App\Traits\Cacheable', class_uses_recursive($model))) {
            return $model->getCached($id);  // Changed from static to instance call
        }

        return $model->findOrFail($id);
    }
}
