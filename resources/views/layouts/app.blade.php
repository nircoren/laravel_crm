<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<style>
    h1 {
        text-align: center;
    }

    main {
        font-family: Arial;
    }
</style>
<body>
<header>
    <h1>Calls CRM</h1>
</header>
<main>
    @yield('content')
</main>
</body>
</html>
