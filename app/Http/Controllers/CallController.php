<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallRequest;
use App\Http\Resources\CallResource;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Agent;
use App\Services\FilterService;

class CallController {

    public const array RELATED_MODEL_CLASS_MAP = [
        'agents' => Agent::class,
        'customers' => Customer::class,
        'calls' => Call::class,
    ];

    private const array SELECT_FIELDS = [
        "calls.id AS call_id",
        "calls.created_at AS created_at",
        "calls.duration AS duration",
        "calls.notes AS notes",
        "calls.type AS type",
        "agents.name AS agent_name",
        "customers.name AS customer_name",
        "customers.phone AS customer_phone",
    ];

    public const array GRID_HEADERS = [
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
    public function index(CallRequest $request) {
        $query = Call::query();

        $query->select(self::SELECT_FIELDS);

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
                FilterService::dynamicFilterQuery($request->input('filters'), $query, self::RELATED_MODEL_CLASS_MAP);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }

        $perPage = min($request->input('per_page', self::DEFAULT_ITEMS_PER_PAGE), self::MAX_ITEMS_PER_PAGE);
        $query->orderBy('calls.id', 'desc');
        $calls = $query->paginate($perPage);
        return CallResource::collection($calls);
    }
}
