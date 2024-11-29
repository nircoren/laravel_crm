<?php

namespace App\Http\Requests;

use App\Enums\CallType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CallRequest extends FormRequest
{
    // Right now I don't validate most fields from other models, but I should
    public function rules()
    {
        return [
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'filters' => 'array',
            'filters.calls.type' => ['nullable', 'string', Rule::in(CallType::values())],
            'filters.calls.duration' => 'integer',
            'filters.calls.notes' => 'string',
            'filters.calls.agent_id' => 'integer',
            'filters.calls.customer_id' => 'integer',
            'filters.*.*' => 'string',
        ];
    }
}
