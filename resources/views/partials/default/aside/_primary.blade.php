<div class="flex flex-col justify-between bg-brand text-center w-20">
    <div class="pt-7">
        <a class="flex-shrink-0 flex items-center px-4" href="{{ route('shopper.dashboard') }}">
            <img class="h-15 w-auto" src="{{ asset('shopper/images/logo-white.svg') }}" alt="Shopper" />
        </a>
        <div class="h-full pt-5 pb-4 overflow-y-auto">
            <nav class="mt-5 px-2 space-y-1 flex flex-col items-center">
                <a href="#" class="group block p-2 text-base leading-6 font-medium rounded-md text-white bg-secondary focus:outline-none focus:bg-brand-hover transition ease-in-out duration-150">
                    <svg class="h-6 w-6 text-white group-hover:text-white group-focus:text-white transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M9 21h6"/>
                    </svg>
                </a>
                <a href="#" class="group block p-2 text-base leading-6 font-medium rounded-md text-white hover:text-white hover:bg-brand-hover focus:outline-none focus:text-white focus:bg-brand-hover transition ease-in-out duration-150">
                    <svg class="h-6 w-6 text-white group-hover:text-white group-focus:text-white transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </a>
                <a href="#" class="group block p-2 text-base leading-6 font-medium rounded-md text-white hover:text-white hover:bg-brand-hover focus:outline-none focus:text-white focus:bg-brand-hover transition ease-in-out duration-150">
                    <svg class="h-6 w-6 text-white group-hover:text-white group-focus:text-white transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </a>
                <a href="#" class="group block p-2 text-base leading-6 font-medium rounded-md text-white hover:text-white hover:bg-brand-hover focus:outline-none focus:text-white focus:bg-brand-hover transition ease-in-out duration-150">
                    <svg class="h-6 w-6 text-white group-hover:text-white group-focus:text-white transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </a>
                <a href="#" class="group block p-2 text-base leading-6 font-medium rounded-md text-white hover:text-white hover:bg-brand-hover focus:outline-none focus:text-white focus:bg-brand-hover transition ease-in-out duration-150">
                    <svg class="h-6 w-6 text-white group-hover:text-white group-focus:text-white transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </a>
                <a href="#" class="group block p-2 text-base leading-6 font-medium rounded-md text-white hover:text-white hover:bg-brand-hover focus:outline-none focus:text-white focus:bg-brand-hover transition ease-in-out duration-150">
                    <svg class="h-6 w-6 text-white group-hover:text-white group-focus:text-white transition ease-in-out duration-150" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </a>
            </nav>
        </div>
    </div>
    <div class="flex-shrink-0 flex flex-col items-center space-x-6 pb-4">
        <button type="button" class="block h-9 w-9 border-2 rounded-full border-white block focus:outline-none">
            <img class="bg-cover rounded-full" src="{{ $logged_in_user->picture }}" alt="{{ $logged_in_user->email }}" />
        </button>
    </div>
</div>
