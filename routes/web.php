<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CallController;
use App\Services\FilterService;
use \App\Http\Requests\CallRequest;
use Illuminate\Support\Facades\Route;


Route::get('/', function (CallRequest $request, CallController $callController, AgentController $agentController) {
    $agents = $agentController->index($request);
    $calls = $callController->index($request);
    $modelsFieldMap = FilterService::getModelsFields();

    return view('index', compact('agents', 'calls', 'modelsFieldMap'));
})->name('index');
