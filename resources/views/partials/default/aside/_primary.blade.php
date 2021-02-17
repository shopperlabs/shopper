<div class="flex flex-col justify-between bg-blue-800 text-center w-16">
    <div class="pt-3">
        <a class="flex-shrink-0 flex items-center px-4" href="{{ route('shopper.dashboard') }}">
            <x-shopper-application-logo class="h-12 w-auto" />
        </a>
        <div class="h-full pt-5 pb-4 overflow-y-auto">
            {!! $primaryMenu->asUl(['class' => 'primary-menu mt-5 px-2 space-y-2 flex flex-col items-center']) !!}
        </div>
    </div>
    <div class="flex-shrink-0 flex flex-col items-center space-y-3 pb-4">
        @can('access_setting')
            <a href="{{ route('shopper.settings.index') }}" class="block p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-blue-700 @if(request()->routeIs('shopper.settings*')) bg-blue-700 @endif focus:outline-none focus:bg-blue-900 transition ease-in-out duration-150">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </a>
        @endcan
        <a href="https://docs.laravelshopper.io" target="_blank" class="block p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-900 transition ease-in-out duration-150">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
        </a>
    </div>
</div>
