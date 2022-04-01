<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-DNS-Prefetch-Control" content="on">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', app_name()) | {{ __('Shopper E-commerce') }}</title>
    <meta name="locale" content="{{ app()->getLocale() }}">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="dashboard-url" content="{{ config('app.url').'/'.shopper_prefix() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('shopper::includes._favicons')

    <link rel="dns-prefetch" href="{{ config('app.url') }}">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />
    <!--end::Fonts -->
    @stack('styles')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">
    <livewire:styles />

    <!-- Scripts -->
    <wireui:scripts />
    <livewire:scripts />
    <script src="{{ mix('/js/shopper.js','shopper') }}" defer></script>

    @include('shopper::includes._additional-styles')
</head>
<body class="bg-secondary-100 font-sans antialiased dark:bg-secondary-900">

    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false, modalDemo: false }" @keydown.window.escape="sidebarOpen = false">
        <!-- Off-canvas menu for mobile -->
        @include('shopper::partials.default.sidebar-mobile')

        <!-- Static sidebar for desktop -->
        @include('shopper::partials.default.sidebar')

        <div class="flex flex-col w-0 flex-1 overflow-hidden overflow-y-auto">
            <div class="sticky top-0 z-10 shrink-0 flex h-16 bg-white dark:bg-secondary-900 md:bg-transparent md:dark:bg-transparent backdrop-filter backdrop-blur-md shadow md:shadow-none md:py-4 md:h-auto border-b border-transparent md:border-secondary-200 dark:border-secondary-700">
                <button @click.stop="sidebarOpen = true" class="px-4 border-r border-secondary-200 dark:border-secondary-700 text-secondary-500 focus:outline-none focus:bg-secondary-100 dark:focus:bg-secondary-800 focus:text-secondary-600 dark:focus:text-secondary-500 md:hidden" aria-label="Open sidebar">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex-1 px-4 sm:px-6 md:px-8 flex justify-between">
                    <div class="flex-1 flex">
                        <form class="md:hidden w-full flex" action="#" method="GET">
                            <div class="relative w-full text-secondary-400 focus-within:text-secondary-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                    </svg>
                                </div>
                                <input aria-label="{{ __('Search') }}" id="search_field" class="block w-full h-full pl-8 pr-3 py-2 border-transparent dark:bg-secondary-900 text-secondary-900 dark:text-white placeholder-secondary-500 focus:outline-none focus:placeholder-secondary-400 focus:ring-0 focus:border-transparent sm:text-sm" placeholder="{{ __('Search') }}" type="search" />
                            </div>
                        </form>
                        <div class="hidden md:block relative w-full max-w-md">
                            {{-- Search Component --}}
                        </div>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6 space-x-4">
                        <a x-tooltip.raw="{{ __('View Site') }}" href="{{ url('/') }}" target="_blank" class="text-secondary-500 hover:text-secondary-900 dark:text-secondary-400 dark:hover:text-white">
                            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                        <!-- Profile dropdown -->
                        <livewire:shopper-account.dropdown />
                    </div>
                </div>
            </div>
            <main class="flex-1 relative z-0 focus:outline-none py-3 lg:py-6" tabindex="0">
                <div class="relative max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 min-h-(screen-content)">
                    <!-- Content -->
                    @yield('content')
                    <!-- /End content -->
                </div>
            </main>
        </div>

        <x-shopper-wip />

    </div>

    <x-notifications z-index="z-50" />

    <x-shopper-alert />

    @livewire('livewire-ui-modal')
    @stack('scripts')
    @include('shopper::includes._additional-scripts')

</body>
</html>
