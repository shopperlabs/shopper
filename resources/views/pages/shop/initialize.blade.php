<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-DNS-Prefetch-Control" content="on">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __("Shop Initialization") }} | Shopper E-commerce</title>
    <meta name="description" content="@yield('meta_description', 'Laravel Shopper Admin Panel')">
    <meta name="author" content="@yield('meta_author', 'Arthur Monney')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ config('app.url') }}">
    <meta name="dashboard-url" content="{{ config('app.url').'/'.config('shopper.prefix') }}">
    <meta name="api-connection" content="{{ config('shopper.api_connection') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('shopper/images/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('shopper/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('shopper/images/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('shopper/images/favicons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('shopper/images/favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="apple-mobile-web-app-title" content="{{ app_name() }}">
    <meta name="application-name" content="{{ app_name()  }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="dns-prefetch" href="{{ config('app.url') }}">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">
    <link rel="stylesheet" href="https://unpkg.com/trix@1.2.3/dist/trix.css">
    <!--end::Fonts -->
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">
    <livewire:styles />
</head>
<body class="text-gray-500 leading-normal font-body transition ease-in-out duration-700">

    <div class="min-h-screen bg-gray-100">
        <nav id="navbar" x-data="{ open: false }" class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <svg class="block lg:hidden h-8 w-auto" fill="none" viewBox="0 0 40 40">
                                <path d="M19.636 30.552c-.007 1.122-.778 2.046-1.793 2.206-.029.008-.058.008-.08.015-.395.046-.798.077-1.209.07-5.543-.024-10.014-4.811-9.992-10.69 0-.359.021-.718.05-1.061 0-.016 0-.031.007-.039 0-.03.007-.06.007-.091v-.008c.015-.122.044-.244.08-.359.273-.908 1.08-1.565 2.023-1.557 1.101.007 2.001.9 2.102 2.046.007.069.007.137.007.214 0 .076-.007.145-.014.221-.015.153-.03.306-.036.458 0 .069 0 .145-.008.214-.014 3.398 2.57 6.162 5.774 6.177.21 0 .418-.008.627-.03.014 0 .028-.008.043-.008.029 0 .05-.008.08-.008.071-.008.136-.008.208-.008 1.18-.007 2.124 1 2.124 2.238z" fill="#1C64F2"/>
                                <path d="M26.513 22.275v.19a11.109 11.109 0 0 1-.835 4.024c-1.397 3.352-4.4 5.827-7.956 6.292 1.015-.168 1.793-1.084 1.8-2.2.007-1.236-.936-2.252-2.11-2.252-.071 0-.143 0-.208.008 2.369-.313 4.298-2.138 4.888-4.543l-8.761-6.726-.994-.764-.014-.007-2.563-1.97-3.24-2.49-.007-.587c.029-1.42.317-2.772.82-4.001 1.383-3.36 4.37-5.849 7.92-6.33-1.008.168-1.786 1.1-1.786 2.215 0 1.236.95 2.244 2.117 2.244.072 0 .286-.032.35-.04-2.36.321-4.418 2.178-5.008 4.576 0 .007 0 .015-.007.022l3.744 2.848.007.008L26.49 21.87c.022.13.022.267.022.405z" fill="#1E429F"/>
                                <path d="M26.48 11.494c0 .36-.015.718-.051 1.062v.038c0 .03-.007.06-.007.091v.008a1.984 1.984 0 0 1-.08.359c-.273.908-1.072 1.573-2.022 1.573-1.102 0-2.01-.894-2.11-2.031-.007-.069-.007-.138-.007-.214 0-.076 0-.145.007-.222.015-.152.029-.305.029-.458v-.213c0-3.398-2.599-6.154-5.803-6.154-.209 0-.417.015-.626.038-.014 0-.029.007-.043.007-.03 0-.05.008-.08.008-.071.008-.136.008-.208.008-1.167 0-2.117-1.008-2.117-2.245 0-1.122.77-2.046 1.786-2.214.028-.008.057-.008.079-.015.396-.054.799-.077 1.21-.077 5.55.008 10.042 4.772 10.042 10.651z" fill="#1C64F2"/>
                            </svg>
                            <svg class="hidden lg:block h-8 w-auto" width="152" height="30" fill="none">
                                <path d="M12.798 27.806c-.007 1.046-.751 1.907-1.732 2.057-.028.007-.056.007-.077.014a9.059 9.059 0 0 1-1.169.064C4.463 29.92.142 25.458.162 19.979c0-.335.021-.67.05-.99 0-.014 0-.028.006-.035 0-.029.007-.057.007-.086v-.007c.014-.114.042-.227.077-.334.264-.847 1.043-1.459 1.955-1.452 1.064.007 1.934.84 2.032 1.907.007.064.007.128.007.2 0 .07-.007.135-.014.206-.014.142-.028.284-.035.427 0 .064 0 .135-.007.2-.014 3.166 2.484 5.742 5.58 5.756.202 0 .404-.007.606-.029.014 0 .027-.007.041-.007.028 0 .049-.007.077-.007.07-.007.132-.007.202-.007 1.14-.007 2.052.932 2.052 2.085z" fill="#1C64F2"/>
                                <path d="M19.445 20.093v.177a10.037 10.037 0 0 1-.807 3.75c-1.35 3.125-4.252 5.43-7.69 5.865a2.08 2.08 0 0 0 1.74-2.05c.007-1.153-.904-2.1-2.038-2.1-.07 0-.14 0-.202.008 2.29-.292 4.154-1.993 4.724-4.234l-8.467-6.27-.96-.711-.015-.007-2.477-1.836-3.13-2.32-.008-.548C.143 8.493.422 7.234.91 6.088c1.336-3.13 4.223-5.45 7.653-5.9-.974.157-1.725 1.026-1.725 2.065 0 1.152.918 2.092 2.045 2.092.07 0 .277-.03.34-.038-2.283.3-4.27 2.03-4.841 4.265 0 .007 0 .014-.007.021l3.618 2.654.007.007 11.425 8.461c.02.121.02.25.02.378z" fill="#1E429F"/>
                                <path d="M19.413 10.044c0 .335-.014.67-.05.99v.035c0 .029-.006.057-.006.086v.007a1.8 1.8 0 0 1-.077.334c-.264.847-1.036 1.466-1.955 1.466-1.064 0-1.941-.832-2.039-1.893-.007-.064-.007-.128-.007-.199 0-.071 0-.135.007-.206a4.4 4.4 0 0 0 .028-.427v-.2c0-3.166-2.512-5.735-5.608-5.735-.202 0-.403.014-.605.035-.014 0-.028.008-.042.008-.028 0-.049.007-.077.007-.07.007-.132.007-.201.007-1.128 0-2.046-.94-2.046-2.092 0-1.046.744-1.907 1.726-2.064.027-.007.055-.007.076-.014a9.05 9.05 0 0 1 1.17-.071c5.364.007 9.706 4.447 9.706 9.926z" fill="#1C64F2"/>
                                <path d="M45.163 26.794h-1.696v-11.55H29.383v11.55h-1.679V3.308h1.68v10.423h14.083V3.308h1.696v23.486zM72.262 15.021c0 3.725-.952 6.673-2.865 8.843-1.913 2.17-4.553 3.255-7.92 3.255-3.349 0-5.989-1.085-7.902-3.255-1.913-2.17-2.865-5.126-2.865-8.877 0-3.733.96-6.68 2.89-8.825 1.93-2.145 4.561-3.221 7.911-3.221 3.367 0 5.998 1.076 7.903 3.238 1.896 2.153 2.848 5.1 2.848 8.842zm-19.723 0c0 3.366.769 5.964 2.306 7.8 1.537 1.837 3.75 2.751 6.633 2.751 2.898 0 5.12-.914 6.658-2.733 1.537-1.82 2.297-4.426 2.297-7.826 0-3.383-.769-5.98-2.297-7.783-1.537-1.803-3.743-2.709-6.625-2.709-2.865 0-5.079.915-6.64 2.734-1.555 1.829-2.332 4.409-2.332 7.766zM92.311 10.058c0 2.272-.793 4.006-2.372 5.22-1.587 1.213-3.826 1.81-6.725 1.81h-3.726v9.706H77.81V3.308h5.906c5.73 0 8.596 2.247 8.596 6.75zM79.48 15.679h3.316c2.715 0 4.678-.436 5.89-1.307 1.211-.871 1.812-2.29 1.812-4.246 0-1.811-.568-3.153-1.712-4.024-1.145-.871-2.915-1.307-5.313-1.307H79.48v10.884zM112.068 10.058c0 2.272-.794 4.006-2.373 5.22-1.587 1.213-3.826 1.81-6.725 1.81h-3.725v9.706h-1.68V3.308h5.907c5.722 0 8.596 2.247 8.596 6.75zm-12.832 5.621h3.317c2.715 0 4.678-.436 5.889-1.307 1.211-.871 1.813-2.29 1.813-4.246 0-1.811-.568-3.153-1.713-4.024-1.144-.871-2.915-1.307-5.313-1.307h-3.993v10.884zM130.487 26.794h-13.173V3.308h13.173v1.513h-11.494v8.885h10.851v1.512h-10.851V25.29h11.494v1.503zM137.63 16.56v10.234h-1.679V3.308h5.739c2.99 0 5.196.539 6.632 1.615 1.429 1.076 2.147 2.7 2.147 4.861 0 1.572-.426 2.905-1.278 3.981-.852 1.085-2.147 1.854-3.884 2.333l6.549 10.696h-2.03l-6.215-10.235h-5.981zm0-1.41h4.603c2.03 0 3.608-.445 4.736-1.325 1.12-.88 1.679-2.187 1.679-3.913 0-1.785-.551-3.092-1.645-3.904-1.103-.812-2.891-1.222-5.38-1.222h-3.993V15.15z" fill="#1E429F"/>
                            </svg>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <div @click.away="open = false" class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = !open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out" id="user-menu" aria-label="User menu" aria-haspopup="true" x-bind:aria-expanded="open">
                                    <img class="h-8 w-8 rounded-full" src="{{ $logged_in_user->picture }}" alt="">
                                </button>
                            </div>
                            <div x-show="open" x-description="Profile dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg" style="display: none;">
                                <div class="py-1 rounded-md bg-white shadow-xs">
                                    <a
                                       href="{{ route('logout') }}"
                                       class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                       onclick="document.getElementById('logout-form').submit();"
                                    >
                                        {{ __("Sign out") }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
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
                        <a href="{{ route('logout') }}"
                           class="mt-1 block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:text-gray-800 focus:bg-gray-100 transition duration-150 ease-in-out" role="menuitem"
                           onclick="document.getElementById('sm-logout-form').submit();"
                        >
                            {{ __("Sign out") }}
                        </a>
                        <form id="sm-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-5 sm:py-10">
            <div class="max-w-7xl mx-auto">
                <!-- Replace with your content -->
                    <livewire:initialization />
                <!-- /End replace -->
            </div>
        </main>
    </div>

    <livewire:scripts />
    <script src="{{ mix('/js/application.js', 'shopper') }}"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>
    <script src="https://unpkg.com/trix@1.2.3/dist/trix.js"></script>
    <script>
      $(document).ready(function() {
        $(window).scroll(function(){
          var winTop = $(window).scrollTop();
          if(winTop >= 50) {
            $('#sticky-header').addClass('bg-white shadow-md sticky top-6 p-4 rounded-md mx-8');
          } else {
            $('#sticky-header').removeClass('bg-white shadow-md sticky top-6 p-4 rounded-md mx-8');
          }
        });
      });
    </script>

</body>
</html>
