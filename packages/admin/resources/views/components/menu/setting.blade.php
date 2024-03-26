@props(['menu'])

<a
    href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
    @if(! $menu['route']) x-on:click.prevent="modalDemo = true" @endif
    wire:navigate
    class="p-3 flex items-start space-x-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-200"
>
    <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-lg bg-primary-600 text-white sm:h-12 sm:w-12 ">
        {{ $menu['icon'] }}
    </div>
    <div class="space-y-1">
        <p class="inline-flex items-center font-medium text-gray-900 dark:text-white">
            {{ __($menu['name']) }}

            @if(! $menu['route'])
                <span class="ml-2.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-primary-100 text-primary-800">
                    {{ __('shopper::layout.soon') }}
                </span>
            @endif
        </p>
        <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ __($menu['description']) }}
        </p>
    </div>
</a>
