<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CallController;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function (Request $request, CallController $callController, AgentController $agentController) {
    $agents = $agentController->index($request);
    $calls = $callController->index($request);
    $headers = $callController->gridHeaders;
    $models = $callController->getModelFieldsForDynamicFiltering();

    return view('index', compact('agents', 'calls', 'headers', 'models'));
})->name('index');
