@props([
    'back',
    'current' => null,
])

<div {{ $attributes }}>
    <nav class="sm:hidden">
        <x-shopper::link
            href="{{ $back }}"
            class="flex items-center text-sm font-medium leading-5 text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-500"
        >
            <x-untitledui-chevron-left
                class="-ml-1 mr-1 size-5 shrink-0 text-gray-400 dark:text-gray-500"
                aria-hidden="true"
            />
            {{ __('shopper::layout.back') }}
        </x-shopper::link>
    </nav>
    <nav class="hidden items-center gap-x-2 text-sm font-medium leading-5 sm:flex">
        <x-shopper::link
            href="{{ route('shopper.dashboard') }}"
            class="inline-flex items-center rounded-md p-1.5 text-sm leading-5 text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800"
        >
            <x-untitledui-home-line class="size-5" aria-hidden="true" />
        </x-shopper::link>

        {{ $slot }}

        @if ($current)
            <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <span
                aria-current="page"
                class="inline-block rounded-md bg-gray-50 px-2 py-1.5 text-gray-700 dark:bg-gray-800 dark:text-gray-300"
            >
                {{ $current }}
            </span>
        @endif
    </nav>
</div>
