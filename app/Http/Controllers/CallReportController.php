<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallReportRequest;
use App\Services\CallReportService;
use App\Services\CallReportModelService;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class CallReportController extends Controller {

    public function index(CallReportRequest $request, CallReportService $callReportService):  View{
        return view('index', [
            'agents' => $callReportService->getAgents($request),
            'calls' => $callReportService->getCalls($request),
            'modelsFieldMap' => CallReportModelService::getModelsFields()
        ]);
    }
}
