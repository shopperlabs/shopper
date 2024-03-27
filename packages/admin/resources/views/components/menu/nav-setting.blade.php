@props(['menu'])

<a href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
   @class([
        'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium select-none',
        'border-primary-500 text-primary-600 current' => $menu['route'] && request()->routeIs($menu['route'] . '*'),
        'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:text-gray-300' => ! $menu['route'] || ! request()->routeIs($menu['route'] . '*'),
    ])
       wire:navigate
    @if($menu['route'] && request()->routeIs($menu['route'] . '*')) aria-current="page" @endif
>
    {{ __($menu['name']) }}

    @if(! $menu['route'])
        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 8 8" aria-hidden="true">
                <circle cx="4" cy="4" r="3" />
            </svg>
            {{ __('shopper::layout.soon') }}
        </span>
    @endif
</a>
