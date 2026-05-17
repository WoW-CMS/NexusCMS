<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class ModuleCrudService
{
    private static array $cache = [];

    /**
     * Read and return the parsed admin-crud.json for a module.
     */
    public static function getConfig(string $moduleName): ?array
    {
        if (isset(self::$cache[$moduleName])) {
            return self::$cache[$moduleName];
        }

        $path = base_path("app/Modules/{$moduleName}/admin-crud.json");

        if (!File::exists($path)) {
            return null;
        }

        $decoded = json_decode(File::get($path), true);

        if (!is_array($decoded)) {
            return null;
        }

        self::$cache[$moduleName] = $decoded;

        return $decoded;
    }

    /**
     * Resolve the Eloquent model class defined in the CRUD config.
     */
    public static function resolveModel(array $config): Model
    {
        $modelClass = trim((string) ($config['model'] ?? ''));

        if ($modelClass === '' || !class_exists($modelClass)) {
            abort(500, "Model class '{$modelClass}' not found.");
        }

        return new $modelClass();
    }

    /**
     * Build Laravel validation rules from the form fields definition.
     */
    public static function buildValidationRules(array $config, bool $isUpdate = false): array
    {
        $rules = [];

        foreach ($config['form']['sections'] ?? [] as $section) {
            foreach ($section['fields'] ?? [] as $field) {
                $name = (string) ($field['name'] ?? '');
                if ($name === '' || ($field['type'] ?? '') === 'hidden') {
                    continue;
                }

                $fieldRules = [];

                $required = (bool) ($field['required'] ?? false);
                $fieldRules[] = $required ? 'required' : 'nullable';

                $fieldRules = array_merge($fieldRules, self::typeRules($field, $isUpdate));

                $rules[$name] = $fieldRules;
            }
        }

        return $rules;
    }

    /**
     * Paginated list of records for the module model.
     */
    public static function paginate(array $config, ?string $search = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $model = self::resolveModel($config);
        $query = $model->newQuery();

        // Support both formats:
        //   "searchable": ["col1", "col2"]       — array of columns
        //   "searchable": true, "search_columns": ["col1", "col2"]  — legacy boolean + separate key
        $searchableCfg = $config['list']['searchable'] ?? [];
        $searchColumns = is_array($searchableCfg)
            ? $searchableCfg
            : (is_array($config['list']['search_columns'] ?? null) ? $config['list']['search_columns'] : []);

        if ($search !== null && $search !== '' && count($searchColumns) > 0) {
            $query->where(function ($q) use ($search, $searchColumns) {
                foreach ($searchColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        $orderBy  = (string) ($config['list']['order_by'] ?? '');
        $orderDir = strtolower((string) ($config['list']['order_dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';
        if ($orderBy !== '') {
            $query->orderBy($orderBy, $orderDir);
        }

        $perPage = (int) ($config['list']['per_page'] ?? 15);

        return $query->paginate($perPage);
    }

    /**
     * Find a single record by ID.
     */
    public static function find(array $config, int|string $id): Model
    {
        $model = self::resolveModel($config);
        $record = $model->newQuery()->findOrFail($id);

        return $record;
    }

    /**
     * Store a new record.
     */
    public static function store(array $config, array $data): Model
    {
        $model = self::resolveModel($config);
        $record = $model->newInstance($data);
        $record->save();

        return $record;
    }

    /**
     * Update an existing record.
     */
    public static function update(array $config, int|string $id, array $data): Model
    {
        $record = self::find($config, $id);
        $record->fill($data);
        $record->save();

        return $record;
    }

    /**
     * Delete a record by ID.
     */
    public static function destroy(array $config, int|string $id): void
    {
        $record = self::find($config, $id);
        $record->delete();
    }

    /**
     * Resolve options for a relation field.
     */
    public static function resolveRelationOptions(array $field): array
    {
        $modelClass = trim((string) ($field['model'] ?? ''));
        $valueField = (string) ($field['value_field'] ?? 'id');
        $labelField = (string) ($field['label_field'] ?? 'name');

        if ($modelClass === '' || !class_exists($modelClass)) {
            return [];
        }

        return $modelClass::query()
            ->get([$valueField, $labelField])
            ->map(fn ($item) => ['value' => $item->{$valueField}, 'label' => $item->{$labelField}])
            ->all();
    }

    public static function flushCache(): void
    {
        self::$cache = [];
    }

    private static function enumRules(array $field): array
    {
        $options = $field['options'] ?? [];
        $values = array_map(
            fn($opt) => is_array($opt) ? (string) ($opt['value'] ?? '') : (string) $opt,
            $options
        );

        if (empty($values)) {
            return ['string'];
        }

        return ['string', 'in:' . implode(',', $values)];
    }

    private static function typeRules(array $field, bool $isUpdate): array
    {
        $rules = match ($field['type'] ?? 'text') {
            'text', 'slug'         => ['string', 'max:' . (int) ($field['max'] ?? 255)],
            'textarea', 'richtext' => ['string'],
            'number'               => ['numeric'],
            'toggle', 'checkbox'   => ['boolean'],
            'date'                 => ['date'],
            'datetime'             => ['date'],
            'email'                => ['email', 'max:' . (int) ($field['max'] ?? 255)],
            'url'                  => ['url',   'max:' . (int) ($field['max'] ?? 255)],
            'image'                => $isUpdate ? ['nullable', 'image', 'max:2048'] : ['image', 'max:2048'],
            'file'                 => $isUpdate ? ['nullable', 'file',  'max:10240'] : ['file', 'max:10240'],
            'select'               => [],
            'relation'             => ['integer'],
            'enum'                 => self::enumRules($field),
            default                => [],
        };

        // Append min/max constraints from config for numeric types
        if (in_array($field['type'] ?? '', ['number', 'text', 'textarea', 'email', 'url', 'slug'], true)) {
            if (isset($field['min']) && is_numeric($field['min'])) {
                $rules[] = ($field['type'] === 'number') ? 'min:' . $field['min'] : 'min:' . (int) $field['min'];
            }
            if (isset($field['max']) && is_numeric($field['max']) && ($field['type'] === 'number')) {
                $rules[] = 'max:' . $field['max'];
            }
        }

        return $rules;
    }
}
