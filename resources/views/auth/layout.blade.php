<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <meta name="robots" content="noindex">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="dashboard-url" content="{{ config('app.url').'/'.config('shopper.prefix') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-connection" content="{{ config('shopper.api_connection') }}">
    <title>@yield('title') | Shopper Login</title>

    @include('shopper::includes._favicons')

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">

    @include('shopper::includes._additional-styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 transition duration-200 ease-in-out">

    @yield('content')

    @stack('scripts')

    <script src="{{ mix('/js/shopper.js', 'shopper') }}"></script>
    @include('shopper::includes._additional-scripts')
</body>
</html>
