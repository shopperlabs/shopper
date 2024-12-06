@props([
    'menu',
])

<x-shopper::link
    :href="$menu['route'] ? route($menu['route']) : '#'"
    class="flex items-start space-x-4 rounded-lg p-3 transition duration-200 ease-in-out hover:bg-gray-50 dark:hover:bg-white/5"
>
    <div class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-white">
        {{ svg($menu['icon'], 'size-6') }}
    </div>
    <div class="space-y-1">
        <p class="inline-flex items-center font-medium text-gray-900 dark:text-white">
            {{ __($menu['name']) }}

            @if (! $menu['route'])
                <span
                    class="ml-2.5 inline-flex items-center rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium leading-4 text-primary-800"
                >
                    {{ __('shopper::words.soon') }}
                </span>
            @endif
        </p>
        <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ __($menu['description']) }}
        </p>
    </div>
</x-shopper::link>
