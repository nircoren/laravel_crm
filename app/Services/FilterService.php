<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FilterService {

    /**
     * Dynamic filter based on models and fields.
     * Filters are passed in the following format:
     * filter[modelName][field] = value
     */
    /**
     * @throws \Exception
     */
    public static function dynamicFilterQuery(array $requestFilters, Builder &$query, $modelClasses): void {
        foreach ($requestFilters as $modelName => $filters) {
            $modelClass = $modelClasses[$modelName] ?? null;
            if (!$modelClass) {
                throw new \Exception("Model {$modelName} not found");
            }
            /* @var Model $model */
            $model = new $modelClass();
            $modelTable = $model->getTable();
            $modelFields = self::getModelFields($model);
            foreach ($filters as $field => $value) {
                // Vaildate field is actually in the model
                if (!in_array($field, $modelFields)) {
                    throw new \Exception("Field {$field} not found in model {$modelName}");
                }

                $query->where($modelTable . '.' . $field, 'LIKE', '%' . $value . '%');
//                $query->where($modelTable . '.' . $field, $value);

            }
        }
    }

    public static function getModelFields(Model $model): array {
        $fillable = $model->getFillable();
        $guarded = $model->getGuarded() === ['*'] ? [] : $model->getGuarded();
        $genericFields = [
            $model->getKeyName(),
        ];
        return array_merge($fillable, $guarded, $genericFields);
    }

    public static function getFieldsForAllModels($modelClasses): array {
        $models = [];
        foreach ($modelClasses as $modelClass) {
            $model = new $modelClass;
            $fields = self::getModelFields($model);
            $models[$model->getTable()] = $fields;
        }
        return $models;
    }
}
