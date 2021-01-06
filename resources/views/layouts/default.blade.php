<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-DNS-Prefetch-Control" content="on">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', app_name()) | Shopper E-commerce</title>
    <meta name="locale" content="{{ app()->getLocale() }}">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="dashboard-url" content="{{ config('app.url').'/'.shopper_prefix() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <!--end::Fonts -->
    @stack('styles')
    @bukStyles(true)
    <livewire:styles />

    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">

    @if(! empty(config('shopper.system.resources.stylesheets')))
        <!-- Additional CSS -->
        @foreach(config('shopper.system.resources.stylesheets') as $css)
            @if (starts_with($css, ['http://', 'https://']))
                <link rel="stylesheet" type="text/css" href="{!! $css !!}">
            @else
                <link rel="stylesheet" type="text/css" href="{{ asset($css) }}">
            @endif
        @endforeach
    @endif
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false, modalDemo: false, }" @keydown.window.escape="sidebarOpen = false">
        <!-- Off-canvas menu for mobile -->
        @include('shopper::partials.default.sidebar-mobile')

        <!-- Static sidebar for desktop -->
        @include('shopper::partials.default.sidebar')

        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="relative flex-shrink-0 flex h-16 bg-white md:bg-transparent shadow md:shadow-none md:py-4 md:h-auto">
                <button @click.stop="sidebarOpen = true" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:bg-gray-100 focus:text-gray-600 md:hidden" aria-label="Open sidebar">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex-1 px-4 sm:px-6 md:px-8 flex justify-between">
                    <div class="flex-1 flex">
                        <form class="md:hidden w-full flex" action="#" method="GET">
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                    </svg>
                                </div>
                                <input aria-label="Search" id="search_field" class="block w-full h-full pl-8 pr-3 py-2 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 sm:text-sm" placeholder="{{ __("Search") }}" type="search">
                            </div>
                        </form>
                        <div class="hidden md:block relative w-full max-w-md">
                            <button class="transition-colors duration-100 ease-in-out text-gray-600 py-2 pr-12 pl-10 block w-full appearance-none leading-normal border border-transparent rounded-md focus:outline-none text-left select-none truncate bg-white border-gray-200 focus:border-gray-300 focus:bg-gray-50">
                                {{ __("Search") }}
                            </button>
                            <div class="pointer-events-none absolute inset-y-0 left-0 pl-4 flex items-center">
                                <svg class="fill-current pointer-events-none text-gray-600 w-4 h-4" viewBox="0 0 20 20">
                                    <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                                </svg>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm sm:leading-5 inline-flex items-center">
                                    <span>
                                        <kbd class="border border-gray-300 mr-1 bg-gray-100 align-middle p-0 inline-flex justify-center items-center text-xs text-center rounded group-hover:border-gray-300 transition duration-150 ease-in-out" style="min-width:1.5em">/</kbd>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <button class="p-1 text-gray-400 rounded-full hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:shadow-outline focus:text-gray-500" aria-label="Notifications">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
                        <livewire:shopper-dropdown />
                    </div>
                </div>
            </div>
            <div class="overflow-y-auto">
                <main class="flex-1 relative z-0 focus:outline-none pt-3 lg:pt-0" tabindex="0">
                    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
                        <!-- Content -->
                        @yield('content')
                        <!-- /End content -->
                    </div>
                </main>
            </div>
        </div>

        <x-shopper-wip />
    </div>
    <x-shopper-notify />
    <x-shopper-alert />

    <livewire:scripts />
    @bukScripts(true)
    <script src="{{ mix('/js/shopper.js','shopper') }}"></script>

    @stack('scripts')

    @if(! empty(config('shopper.system.resources.scripts')))
        <!-- Additional Javascript -->
        @foreach(config('shopper.system.resources.scripts') as $js)
            @if (starts_with($js, ['http://', 'https://']))
                <script type="text/javascript" src="{!! $js !!}"></script>
            @else
                <script type="text/javascript" src="{{ asset($js) }}"></script>
            @endif
        @endforeach
    @endif

</body>
</html>
