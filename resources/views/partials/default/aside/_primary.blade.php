<div class="flex flex-col justify-between bg-brand-500 text-center w-16">
    <div class="pt-2">
        <a class="flex-shrink-0 flex items-center px-4" href="{{ route('shopper.dashboard') }}">
            <img class="h-15 w-auto" src="{{ asset('shopper/images/logo-white.svg') }}" alt="Shopper" />
        </a>
        <div class="h-full pt-5 pb-4 overflow-y-auto">
            {!! $primaryMenu->asUl(['class' => 'primary-menu mt-5 px-2 space-y-2 flex flex-col items-center']) !!}
        </div>
    </div>
    <div class="flex-shrink-0 flex flex-col items-center space-y-3 pb-4">
        <button class="group block p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-brand-400 focus:outline-none focus:bg-brand-400 transition ease-in-out duration-150">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        <button class="group block p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-brand-400 focus:outline-none focus:bg-brand-400 transition ease-in-out duration-150">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </button>
        <livewire:dropdown />
    </div>
</div>
