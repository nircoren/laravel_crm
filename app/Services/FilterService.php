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
    public function dynamicFilterQuery(array $requestFilters, Builder $query): void {
        foreach ($requestFilters as $modelName => $filters) {
            $modelClass = CallReportModelService::MODEL_CLASS_MAP[$modelName];
            /* @var Model $model */
            $model = new $modelClass();
            $modelTable = $model->getTable();
            foreach ($filters as $field => $value) {
//                $query->where($modelTable . '.' . $field, 'LIKE', '%' . $value . '%');
                $query->where($modelTable . '.' . $field, $value);

            }
        }
    }
}
