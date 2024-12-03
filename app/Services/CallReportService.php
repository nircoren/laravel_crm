<?php
namespace App\Services;

use App\Http\Requests\CallReportRequest;
use App\Http\Resources\CallReportResource;
use App\Models\Agent;
use App\Models\Call;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
class CallReportService {
    const int DEFAULT_ITEMS_PER_PAGE = 15;
    const int MAX_ITEMS_PER_PAGE = 100;
    private FilterService $filterService;

    public function __construct(FilterService $filterService) {
        $this->filterService = $filterService;
    }
    public function getCalls(CallReportRequest $request): ResourceCollection {
        $calls = Call::query()
            ->select([
                'calls.id as call_id',
                'calls.created_at',
                'calls.duration',
                'calls.notes',
                'calls.type',
                'agents.name as agent_name',
                'customers.name as customer_name',
                'customers.phone as customer_phone',
            ])
            // Join is more efficient than elequent relationships
            ->join('agents', 'calls.agent_id', '=', 'agents.id')
            ->join('customers', 'calls.customer_id', '=', 'customers.id')
            ->when($request->filled('from_date') || $request->filled('to_date'), function ($q) use ($request) {
                $fromDate = $request->from_date ?? '1970-01-01';
                $toDate = $request->to_date ?? now();
                $q->whereBetween('calls.created_at', [$fromDate, $toDate]);
            })
            ->when(
                $request->has('filters'), function ($q) use ($request) {
                $this->filterService->dynamicFilterQuery($request->input('filters'), $q);
            })
            ->orderByDesc('calls.id')
            ->paginate(min($request->input('per_page', self::DEFAULT_ITEMS_PER_PAGE), self::MAX_ITEMS_PER_PAGE));
        return CallReportResource::collection($calls);
    }
    public function getAgents(CallReportRequest $request = null): Collection {
        return Cache::remember('agents', now()->addHours(2), fn() => Agent::all());
    }
}
