<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-DNS-Prefetch-Control" content="on">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __("Shop Initialization") }} | {{ __('Shopper E-commerce') }}</title>
    <meta name="description" content="@yield('meta_description', 'Laravel Shopper Admin Panel')">
    <meta name="author" content="@yield('meta_author', 'Arthur Monney')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="dashboard-url" content="{{ config('app.url').'/'.shopper_prefix() }}">

    @include('shopper::layouts.favicons')

    <link rel="dns-prefetch" href="{{ config('app.url') }}">
    <!--begin::Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!--end::Fonts -->

    @bukStyles(true)

    <livewire:styles />

    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">
</head>
<body class="text-gray-500 font-sans transition ease-in-out duration-700 dark:text-gray-400">

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav id="navbar" x-data="{ open: false }" class="bg-white shadow-sm dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <x-shopper-application-logo class="w-auto h-9" />
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <div @click.away="open = false" class="ml-3 relative">
                            <div>
                                <button @click="open = !open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 dark:focus:border-gray-700" id="user-menu" aria-label="User menu" aria-haspopup="true" x-bind:aria-expanded="open">
                                    <img class="h-8 w-8 rounded-full" src="{{ $logged_in_user->picture }}" alt="Avatar">
                                </button>
                            </div>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute z-50 right-0 mt-2 w-48 rounded-md shadow-lg" style="display: none;">
                                <div class="py-1 rounded-md bg-white shadow-xs dark:bg-gray-800">
                                    <button
                                       type="button"
                                       class="w-full inline-flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-900"
                                       onclick="document.getElementById('logout-form').submit();"
                                    >
                                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        {{ __('Sign out') }}
                                    </button>
                                    <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <!-- Mobile menu button -->
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" x-bind:aria-label="open ? 'Close main menu' : 'Main menu'" x-bind:aria-expanded="open" aria-label="Main menu">
                            <svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': open, 'block': !open }" class="h-6 w-6 block" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg x-state:on="Menu open" x-state:off="Menu closed" :class="{ 'hidden': !open, 'block': open }" class="h-6 w-6 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-description="Mobile menu, toggle classes based on menu state." x-state:on="Open" x-state:off="closed" :class="{ 'block': open, 'hidden': !open }" class="sm:hidden hidden">
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="{{ $logged_in_user->picture }}" alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-6 text-gray-800">{{ $logged_in_user->full_name }}</div>
                            <div class="text-sm font-medium leading-5 text-gray-500">{{ $logged_in_user->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                        <button
                           type="button"
                           class="mt-1 inline-flex items-center w-full px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:text-gray-800 focus:bg-gray-100 transition duration-150 ease-in-out"
                           role="menuitem"
                           onclick="document.getElementById('sm-logout-form').submit();"
                        >
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Sign out') }}
                        </button>
                        <form id="sm-logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <livewire:shopper-initialization />

    </div>

    <livewire:scripts />

    @bukScripts(true)

    <script src="{{ mix('/js/shopper.js', 'shopper') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>

</body>
</html>
