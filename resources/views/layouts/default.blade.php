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
    <meta name="locale" content="{{ app()->getLocale() }}">
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
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <!--end::Fonts -->
    @stack('styles')
    @livewireStyles
    @notifyCss

    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">

    @if(! empty(config('shopper.resources.stylesheets')))
    <!-- Additional CSS -->
        @foreach(config('shopper.resources.stylesheets') as $css)
            @if (starts_with($js, ['http://', 'https://']))
                <link rel="stylesheet" type="text/css" href="{!! $css !!}">
            @else
                <link rel="stylesheet" type="text/css" href="{{ asset($css) }}">
            @endif
        @endforeach
    @endif
</head>
<body class="h-full bg-background text-primary-text leading-normal font-body transition ease-in-out duration-500 light">

    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false">
        <!-- Off-canvas menu for mobile -->
        @include('shopper::partials.default.sidebar-mobile')

        <!-- Static sidebar for desktop -->
        @include('shopper::partials.default.sidebar')

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="md:hidden px-3 py-2 bg-white shadow-sm">
                <button @click.stop="sidebarOpen = true" class="-ml-0.5 -mt-0.5 h-10 w-10 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:bg-gray-200 transition ease-in-out duration-150">
                    <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
            <div class="overflow-y-auto">
                <main class="flex-1 relative z-0 min-h-screen -mb-12 focus:outline-none pt-3 lg:pt-0" tabindex="0" x-data x-init="$el.focus()">
                    <div class="relative max-w-7xl mx-auto md:pt-6 px-4 sm:px-6 md:px-10 pb-16">
                        <!-- Content -->
                        @yield('content')
                        <!-- /End content -->
                    </div>
                </main>

                @include('shopper::partials.default._footer')
            </div>

        </div>
    </div>

    <script src="{{ mix('/js/shopper.js','shopper')}}"></script>

    @include('notify::messages')
    @stack('scripts')
    @livewireScripts
    @notifyJs

    @if(! empty(config('shopper.resources.scripts')))
        <!-- Additional Javascript -->
        @foreach(config('shopper.resources.scripts') as $js)
            @if (starts_with($js, ['http://', 'https://']))
                <script type="text/javascript" src="{!! $js !!}"></script>
            @else
                <script type="text/javascript" src="{{ asset($js) }}"></script>
            @endif
        @endforeach
    @endif

</body>
</html>
