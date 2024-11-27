<?php

namespace App\Http\Controllers;

use App\Http\Resources\CallResource;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Collection;
use ReflectionClass;
use Illuminate\Support\Facades\Schema;

class CallController {

    public array $models = [
        'agent' => Agent::class,
        'customer' => Customer::class,
        'call' => Call::class,
    ];

    // TODO: make this for every model, then check on the query if model has the filter key.
    protected array $allowedFields = [
        'id',
        'created_at',
        Call::DURATION,
        Call::TYPE,
        Call::CUSTOMER_RELATION_KEY,
        Call::AGENT_RELATION_KEY,
    ];

    /**
     * @throws \Exception
     */
    public function index(Request $request) {
        // Start the query builder
        $query = Call::query();
        $query->from('crm.calls');

        $prefixedFields = array_map(fn($field) => 'calls.' . $field, $this->allowedFields);
        $query->select($prefixedFields);

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if (is_array($request->filters)) {
            $this->genericFilter($request->filters, $query);
        }

        $perPage = $request->input('per_page', 15); // Default to 15 items per page
        $calls = $query->paginate($perPage);

        return CallResource::collection($calls);
    }

    // Generic filter based on models and fields.
    // Join is less readable but more efficient than eloquent loading.
    /**
     * @throws \Exception
     */
    private function genericFilter(array $requestFilters, Builder &$query): void {
        foreach ($requestFilters as $modelName => $filters) {
            $modelClass = $this->models[$modelName] ?? null;
            if (!$modelClass) {
                throw new \Exception('Model not found');
            }
            $model = new $modelClass();
            $joinTable = $model->getTable();
            $query->join($joinTable, 'calls.' . $model->getForeignKey(), '=', $joinTable . '.id');
            $joinTableColumns = Schema::getColumnListing($joinTable); // this is bad..
            foreach ($filters as $field => $value) {
                if (!in_array($field, $joinTableColumns)) {
                    throw new \Exception('Field not found');
                }
                $query->where($joinTable . '.' . $field, $value);
            }
        }
    }
}
