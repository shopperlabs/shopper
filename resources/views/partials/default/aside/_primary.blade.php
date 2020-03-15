<div class="flex flex-col justify-between bg-primary-default text-center w-16">
    <div class="pt-2">
        <a class="flex-shrink-0 flex items-center px-4" href="{{ route('shopper.dashboard') }}">
            <img class="h-15 w-auto" src="{{ asset('shopper/images/logo-white.svg') }}" alt="Shopper" />
        </a>
        <div class="h-full pt-5 pb-4 overflow-y-auto">
            {!! $primaryMenu->asUl(['class' => 'mt-5 px-2 space-y-1 flex flex-col items-center']) !!}
        </div>
    </div>
    <div class="flex-shrink-0 flex flex-col items-center space-y-3 pb-4">
        <button class="group block p-2 text-base leading-6 font-medium rounded-md text-on-primary hover:text-on-primary hover:bg-primary-light focus:outline-none focus:text-on-primary focus:bg-primary-light transition ease-in-out duration-150">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        <button class="group block p-2 text-base leading-6 font-medium rounded-md text-on-primary hover:text-on-primary hover:bg-primary-light focus:outline-none focus:text-on-primary focus:bg-primary-light transition ease-in-out duration-150">
            <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </button>
        <div @click.away="open = false" class="relative" x-data="{ open: false }">
            <div>
                <button @click="open = !open" class="flex border-2 border-transparent  rounded-full relative rounded-full focus:outline-none focus:border-gray-50 transition duration-150 ease-in-out">
                    <img class="h-9 w-9 rounded-full" src="{{ $logged_in_user->picture }}" alt="{{ $logged_in_user->email }}" />
                    <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full text-white shadow-solid bg-green-400"></span>
                </button>
            </div>
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="origin-top-left absolute top-0 left-0 mt-2 w-48 rounded-md shadow-lg"
                style="top: -110px; left: 45px;"
            >
                <div class="py-1 rounded-md bg-white shadow-xs text-left">
                    <a href="#" class="block px-4 py-2 text-sm leading-5 text-primary-text hover:text-brand-400 focus:outline-none transition duration-150 ease-in-out">Your Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm leading-5 text-primary-text hover:text-brand-400 focus:outline-none transition duration-150 ease-in-out">Settings</a>
                    <a
                        href="{{ route('logout') }}"
                        class="block px-4 py-2 text-sm leading-5 text-primary-text hover:text-brand-400 focus:outline-none transition duration-150 ease-in-out"
                        onclick="event.preventDefault(); localStorage.removeItem('user'); localStorage.removeItem('token'); document.getElementById('logout-form').submit();"
                    >
                        Sign out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
