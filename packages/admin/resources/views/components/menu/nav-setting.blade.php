@props(['menu'])

<a href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
   @class([
        'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium select-none',
        'border-primary-500 text-primary-600 current' => $menu['route'] && request()->routeIs($menu['route'] . '*'),
        'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700 dark:text-secondary-400 dark:hover:border-secondary-700 dark:hover:text-secondary-300' => ! $menu['route'] || ! request()->routeIs($menu['route'] . '*'),
    ])
    @if($menu['route'] && request()->routeIs($menu['route'] . '*')) aria-current="page" @endif
>
    {{ __($menu['name']) }}

    @if(! $menu['route'])
        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-300">
            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-secondary-400 dark:text-secondary-500" fill="currentColor" viewBox="0 0 8 8">
                <circle cx="4" cy="4" r="3" />
            </svg>
            {{ __('shopper::layout.soon') }}
        </span>
    @endif
</a>
