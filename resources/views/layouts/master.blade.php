<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-DNS-Prefetch-Control" content="on">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Shopper E-commerce</title>
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

    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">

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
<body class="page-bg-grey header-mobile--fixed page-content-white subheader--fixed subheader--enabled aside--secondary-enabled offcanvas-panel--left aside--left page--loading">

    @include('shopper::partials.header.header-mobile')

    <div class="grid grid--hor grid--root">

        <div class="grid__item grid__item--fluid grid grid--ver page">

            <button class="aside-close hidden" id="aside_close_btn"><i class="la la-close"></i></button>
            <div class="aside grid__item grid grid--ver" id="aside">

                @include('shopper::partials.aside.primary')
                @include('shopper::partials.aside.secondary')

            </div>

            <div class="grid__item grid__item--fluid grid grid--hor wrapper" id="wrapper">

                <div class="content grid__item grid__item--fluid grid grid--hor" id="content">

                    <div class="container container--fluid grid__item grid__item--fluid">

                        @yield('content')

                    </div>

                </div>

                @include('shopper::partials._footer')

            </div>

        </div>

    </div>

    @include('shopper::partials._scrolltop')

    @include('shopper::partials._notifications')

    @include('shopper::partials._search')

    <script src="{{ asset('shopper/js/vendor.js') }}"></script>
    <script src="{{ asset('shopper/js/template.js') }}"></script>
    <script src="{{ mix('/js/shopper.js','shopper')}}"></script>

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
