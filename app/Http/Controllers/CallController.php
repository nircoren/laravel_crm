<?php

namespace App\Http\Controllers;

use App\Enums\CallType;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CallController {

    public array $modelClasses = [
        'agent' => Agent::class,
        'customer' => Customer::class,
        'call' => Call::class,
    ];

    protected array $selectFields = [
        "calls.id AS call_id",
        "calls.created_at AS created_at",
        "calls." . Call::DURATION . " AS duration",
        "calls." . Call::TYPE . " AS type",
        "agents.name AS agent_name",
        "customers.name AS customer_name",
        "customers.phone AS customer_phone",
    ];


    public array $headers = [
        'ID',
        'Date',
        'Duration',
        'Type',
        'Agent',
        'Customer',
        'Customer Phone',
    ];

    const int DEFAULT_ITEMS_PER_PAGE = 15;

    /**
     * @throws \Exception
     */
    public function index(Request $request) {
        $query = Call::query();
        $query->from('crm.calls');

        // Could make it more strict for other models.
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'filters' => 'array',
            'filters.calls.type' => ['nullable', 'string', Rule::in(CallType::values())],
            'filters.calls.duration' => 'integer',
            'filters.calls.agent_id' => 'integer',
            'filters.calls.customer_id' => 'integer',
            'filters.*.*' => 'string',
        ]);

        $query->select($this->selectFields);

        // Join is less readable but more efficient than with().
        $query->join('agents', "calls.agent_id", '=', "agents.id");
        $query->join('customers', "calls.customer_id", '=', "customers.id");

        if ($request->has('from_date') || $request->has('to_date')) {
            $fromDate = $request->from_date ?? '1970-01-01';
            $toDate = $request->to_date ?? now();

            $query->whereBetween('calls.created_at', [$fromDate, $toDate]);
        }


        if ($request->has('filters')) {
            $this->dynamicFilter($request->input('filters'), $query);
        }
        $perPage = $request->input('per_page', self::DEFAULT_ITEMS_PER_PAGE);
        $query->orderBy('calls.id', 'desc');
        $calls = $query->paginate($perPage);

        $calls->getCollection()->transform(function ($call) {
            return [
                'id' => $call->call_id,
                'date' => $this->formatDate($call->created_at),
                'duration' => $this->formatDuration($call->duration),
                'type' => $call->type,
                'agent' => $call->agent_name,
                'customer' => $call->customer_name,
                'customer_phone' => $call->customer_phone,
            ];
        });

        return $calls;
    }


    // Dynamic filter based on models and fields.
    /**
     * @throws \Exception
     */
    private function dynamicFilter(array $requestFilters, Builder &$query): void {
        foreach ($requestFilters as $modelName => $filters) {
            $modelClass = $this->modelClasses[$modelName] ?? null;
            if (!$modelClass) {
                throw new \Exception('Model not found');
            }
            /* @var Model $model */
            $model = new $modelClass();
            $modelTable = $model->getTable();
            $modelProps = $this->getModelProps($model);
            foreach ($filters as $field => $value) {
                if (!in_array($field, $modelProps)) {
                    throw new \Exception('Field not found');
                }
                // Easly change to LIKE if needed.
                $query->where($modelTable . '.' . $field, $value);
            }
        }
    }

    public function getModelProps(Model $model): array {
        $fillable = $model->getFillable();
        $guarded = $model->getGuarded() === ['*'] ? [] : $model->getGuarded();
        $genericProps = [
            $model->getKeyName(),
            $model->getCreatedAtColumn(),
            $model->getUpdatedAtColumn(),
        ];
        return array_merge($fillable, $guarded, $genericProps);
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
}
