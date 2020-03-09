<!DOCTYPE html>
<html lang="en">
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
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('shopper/images/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('shopper/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('shopper/images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('shopper/images/favicons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('shopper/images/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="apple-mobile-web-app-title" content="{{ app_name() }}">
    <meta name="application-name" content="{{ app_name()  }}">
    <meta name="theme-color" content="#ffffff">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">
</head>
<body class="h-full bg-gray-100 text-gray-600 leading-normal font-body transition ease-in-out duration-700 light">

    <div class="min-h-screen flex overflow-hidden">
        <div class="hidden lg:flex flex-col bg-white px-6 pt-15 pb-20 justify-between relative w-140 shadow-md"></div>

        <main class="relative h-screen mx-auto w-full lg:flex-1 p-10 flex flex-col justify-between overflow-hidden overflow-y-scroll">

            @yield('content')

            <div class="flex justify-between">
                <p class="text-xs text-gray-600">
                    @include('shopper::auth.partials.footer')
                </p>
                <p class="flex space-x-1 text-xs">
                    <a href="https://docs.laravelshopper.com" class="text-brand hover:text-brand-hover" target="_blank">Documentation</a>
                    <span class="text-brand">-</span>
                    <a href="https://twitter.com/laravelshopper" class="text-brand hover:text-brand-hover" target="_blank">Twitter</a>
                    <span class="text-brand">-</span>
                    <a href="https://github.com/laravel-shopper/framework" class="text-brand hover:text-brand-hover" target="_blank">Github</a>
                </p>
            </div>
        </main>
    </div>

    <script src="{{ mix('/js/shopper.js', 'shopper') }}"></script>
</body>
</html>
