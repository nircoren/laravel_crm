<?php

use App\Http\Controllers\CallReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return '<a href="' . route('reports') . '">Go to Reports</a>';
});

Route::get('/reports', [CallReportController::class, 'index'])->name('reports');

