<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AgentController {
    public function index(Request $request = null): \Illuminate\Database\Eloquent\Collection {
        return Cache::remember('agents', now()->addHours(2), function () {
            return Agent::all();
        });
    }
}
