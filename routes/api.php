<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CallController;
use Illuminate\Support\Facades\Route;

Route::get('/calls', [CallController::class, 'index'])->name('calls.index');

Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
