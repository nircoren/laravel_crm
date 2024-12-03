<?php

namespace App\Http\Requests;

use App\Enums\CallType;
use App\Services\CallReportModelService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CallReportRequest extends FormRequest {
    private CallReportModelService $modelService;
    public function __construct(CallReportModelService $modelService) {
        parent::__construct();
        $this->modelService = $modelService;
    }

    public function rules(): array {
        return [
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'filters' => 'array',
            'filters.calls.type' => ['nullable', 'string', Rule::in(CallType::values())],
            'filters.calls.duration' => 'integer',
            'filters.calls.notes' => 'string',
            'filters.calls.agent_id' => 'integer',
            'filters.calls.customer_id' => 'integer',
            'filters.*.*' => function ($attribute, $value, $fail) { // Should add value validation for a more tight validation
                $segments = explode('.', $attribute);
                $modelName = $segments[1];
                $fieldName = $segments[2];
                CallReportModelService::fieldExistsInModel($modelName, $fieldName) || $fail("The field {$fieldName} does not exist in model {$modelName}.");
            },
        ];
    }
}
