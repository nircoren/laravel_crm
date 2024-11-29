<?php

namespace App\Http\Controllers;

use App\Enums\CallType;
use App\Http\Resources\CallResource;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CallController {

    public array $relatedModelClasses = [
        'agents' => Agent::class,
        'customers' => Customer::class,
        'calls' => Call::class,
    ];

    protected array $selectFields = [
        "calls.id AS call_id",
        "calls.created_at AS created_at",
        "calls.duration AS duration",
        "calls.notes AS notes",
        "calls.type AS type",
        "agents.name AS agent_name",
        "customers.name AS customer_name",
        "customers.phone AS customer_phone",
    ];


    public array $gridHeaders = [
        'Call ID',
        'Date',
        'Duration',
        'Notes',
        'Type',
        'Agent',
        'Customer',
        'Customer Phone',
    ];

    const int DEFAULT_ITEMS_PER_PAGE = 15;
    const int MAX_ITEMS_PER_PAGE = 100;

    /**
     * @throws \Exception
     */
    public function index(Request $request): \Illuminate\Pagination\LengthAwarePaginator {
        $query = Call::query();

        // Could make it more strict for other models.
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'filters' => 'array',
            'filters.calls.type' => ['nullable', 'string', Rule::in(CallType::values())],
            'filters.calls.duration' => 'integer',
            'filters.calls.notes' => 'string',
            'filters.calls.agent_id' => 'integer',
            'filters.calls.customer_id' => 'integer',
            'filters.*.*' => 'string',
        ]);

        $query->select($this->selectFields);

        // Join is less readable but more efficient than with().
        $query->join('agents', "calls.agent_id", '=', "agents.id");
        $query->join('customers', "calls.customer_id", '=', "customers.id");

        if ($request->filled('from_date') || $request->filled('to_date')) {
            $fromDate = $request->from_date ?? '1970-01-01';
            $toDate = $request->to_date ?? now();

            $query->whereBetween('calls.created_at', [$fromDate, $toDate]);
        }


        if ($request->has('filters')) {
            try {
                $this->dynamicFilter($request->input('filters'), $query);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }
        $perPage = min($request->input('per_page', self::DEFAULT_ITEMS_PER_PAGE), self::MAX_ITEMS_PER_PAGE);
        $query->orderBy('calls.id', 'desc');
        $calls = $query->paginate($perPage);
        return CallResource::collection($calls);


        $calls->getCollection()->transform(function ($call) {
            return [
                'id' => $call->call_id,
                'date' => $this->formatDate($call->created_at),
                'duration' => $this->formatDuration($call->duration),
                'notes' => $call->notes,
                'type' => $call->type,
                'agent' => $call->agent_name,
                'customer' => $call->customer_name,
                'customer_phone' => $call->customer_phone,
            ];
        });

        return $calls;
    }

    /**
     * Dynamic filter based on models and fields.
     * Filters are passed in the following format:
     * filter[modelName][field] = value
     */
    /**
     * @throws \Exception
     */
    private function dynamicFilter(array $requestFilters, Builder $query): void {
        foreach ($requestFilters as $modelName => $filters) {
            $modelClass = $this->relatedModelClasses[$modelName] ?? null;
            if (!$modelClass) {
                throw new \Exception("Model {$modelName} not found");
            }
            /* @var Model $model */
            $model = new $modelClass();
            $modelTable = $model->getTable();
            $modelFields = $this->getModelFields($model);
            foreach ($filters as $field => $value) {
                if (!in_array($field, $modelFields)) {
                    throw new \Exception("Field {$field} not found in model {$modelName}");
                }

//                $query->where($modelTable . '.' . $field, 'LIKE', '%' . $value . '%');
                $query->where($modelTable . '.' . $field, $value);

            }
        }
    }

    public function getModelFields(Model $model): array {
        $fillable = $model->getFillable();
        $guarded = $model->getGuarded() === ['*'] ? [] : $model->getGuarded();
        $genericFields = [
            $model->getKeyName(),
        ];
        return array_merge($fillable, $guarded, $genericFields);
    }

    private function formatDuration(int $duration): string {
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - $hours * 3600) / 60);
        $seconds = $duration - $hours * 3600 - $minutes * 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function formatDate(string $date): string {
        return date('Y-m-d', strtotime($date));
    }

    public function getModelFieldsForDynamicFiltering() : array {
        $models = [];
        foreach ($this->relatedModelClasses as $modelClass) {
            $model = new $modelClass;
            $fields = $this->getModelFields($model);
            $models[$model->getTable()] = $fields;
        }
        return $models;
    }
}
