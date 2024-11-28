<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CallController;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function (Request $request, CallController $callController, AgentController $agentController) {
    $agents = $agentController->index($request);
    $calls = $callController->index($request);
    $headers = $callController->headers;

    $dynamicFilters = [];
    foreach ($callController->modelClasses as $modelClass) {
        $model = new $modelClass;
        $props = $callController->getModelProps($model);
        $dynamicFilters[$model->getTable()] = $props;
    }

//    $dynamicFilters = [
//        'calls' => ['type' => 'string', 'duration' => 'integer', 'agent_id' => 'integer'],
//        'customer' => ['hi' => 'string', 'there' => 'integer']
//    ];
    return view('index', compact('agents', 'calls', 'headers', 'dynamicFilters'));
})->name('index');
