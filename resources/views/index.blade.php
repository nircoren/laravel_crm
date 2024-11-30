<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CRM')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header>
    <h1>Calls CRM</h1>
</header>
<main>
    <div class="main-container">
        <form id="myForm" method="GET" action="{{ route('index') }}">
            <x-calls-filters
                :agents="$agents"
                :fromDate="request('from_date')"
                :toDate="request('to_date')"
                :selectedAgent="request('filters.call.agent_id')"
                :modelsFieldMap="$modelsFieldMap"
            />
        </form>

        <x-calls-grid
            :calls="$calls"
        />
    </div>

</main>
</body>
</html>


