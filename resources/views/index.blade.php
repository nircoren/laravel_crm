@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="GET" action="{{ route('index') }}">
            <x-call-filters
                :agents="$agents"
                :fromDate="request('from_date')"
                :toDate="request('to_date')"
                :selectedAgent="request('filters.call.agent_id')"
                :dynamicFilters="$dynamicFilters"
            />
        </form>

        <x-calls-grid
            :calls="$calls"
            :headers="$headers"
        />
    </div>
@endsection
