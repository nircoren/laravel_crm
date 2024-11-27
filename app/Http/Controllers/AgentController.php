<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController {
    public function index(Request $request) {
        $query = Agent::query();
        return $query->get();
    }
}
