<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-DNS-Prefetch-Control" content="on">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', app_name()) | Shopper E-commerce</title>
    <meta name="description" content="@yield('meta_description', 'Laravel Shopper Admin Panel')">
    <meta name="author" content="@yield('meta_author', 'Arthur Monney')">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="dashboard-url" content="{{ config('app.url').'/'.config('shopper.prefix') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-connection" content="{{ config('shopper.api_connection') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('shopper/images/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('shopper/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('shopper/images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('shopper/images/favicons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('shopper/images/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="apple-mobile-web-app-title" content="{{ app_name() }}">
    <meta name="application-name" content="{{ app_name() }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="dns-prefetch" href="{{ config('app.url') }}">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <!--end::Fonts -->
    @stack('styles')

    <link rel="stylesheet" type="text/css" href="{{ mix('/css/tailwind.css', 'shopper') }}">

    @if(! empty(config('shopper.resources.stylesheets')))
    <!-- Additional CSS -->
        @foreach(config('shopper.resources.stylesheets') as $css)
            @if (Str::startsWith($js, ['http://', 'https://']))
                <link rel="stylesheet" type="text/css" href="{!! $css !!}">
            @else
                <link rel="stylesheet" type="text/css" href="{{ asset($css) }}">
            @endif
        @endforeach
    @endif
</head>
<body class="">


    <script src="{{ mix('/js/tailwind.js','shopper')}}"></script>

    @stack('scripts')

    @if(! empty(config('shopper.resources.scripts')))
        <!-- Additional Javascript -->
        @foreach(config('shopper.resources.scripts') as $js)
            @if (Str::startsWith($js, ['http://', 'https://']))
                <script type="text/javascript" src="{!! $js !!}"></script>
            @else
                <script type="text/javascript" src="{{ asset($js) }}"></script>
            @endif
        @endforeach
    @endif

</body>
</html>
