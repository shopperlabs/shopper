@props(['menu'])

<a
    href="{{ $menu['route'] ? route($menu['route']) : '#' }}"
    @if(! $menu['route']) x-on:click.prevent="modalDemo = true" @endif
    class="p-3 flex items-start space-x-4 rounded-lg hover:bg-secondary-50 dark:hover:bg-secondary-700 transition ease-in-out duration-200"
>
    <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-md bg-primary-600 text-white sm:h-12 sm:w-12 ">
        <x-dynamic-component :component="$menu['icon']" class="h-6 w-6" />
    </div>
    <div class="space-y-1">
        <p class="inline-flex items-center text-base leading-6 font-medium text-secondary-900 dark:text-white">
            {{ __($menu['name']) }}

            @if(! $menu['route'])
                <span class="ml-2.5 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-primary-100 text-primary-800">
                    {{ __('Soon') }}
                </span>
            @endif
        </p>
        <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
            {{ __($menu['description']) }}
        </p>
    </div>
</a>
