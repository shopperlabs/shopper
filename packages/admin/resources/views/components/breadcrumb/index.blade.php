@props(['back', 'current' => null])

<div {{ $attributes }}>
    <nav class="sm:hidden">
        <a href="{{ $back }}" class="flex items-center text-sm leading-5 font-medium text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-500" wire:navigate>
            <x-untitledui-chevron-left class="shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400 dark:text-gray-500" aria-hidden="true" />
            {{ __('shopper::layout.back') }}
        </a>
    </nav>
    <nav class="hidden sm:flex items-center text-sm leading-5 font-medium gap-x-2">
        <a href="{{ route('shopper.dashboard') }}" class="inline-flex items-center p-1.5 rounded-md text-sm leading-5 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800" wire:navigate>
            <x-untitledui-home-line class="h-5 w-5" aria-hidden="true" />
        </a>

        {{ $slot }}

        @if($current)
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <span aria-current="page" class="inline-block py-1.5 px-2 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md">
                {{ $current }}
            </span>
        @endif
    </nav>
</div>
